<?php

namespace App\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use App\ClientBundle\Entity;
use App\AdminBundle\Business\Recurring\Constants as RecurringConstants;
use App\AdminBundle\Business\Invoice\Constants as InvoiceConstants;
use App\AdminBundle\Business\Order\Constants as OrderConstants;
use App\AdminBundle\Business\ClientProduct\Constants as CPConstants;
use App\AdminBundle\Business;
use App\ClientBundle\Entity\ClientInvoice;

class GenerateRecurringCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $now = new \DateTime('now');

        $this
            ->setName('app_admin:generate_recurring')
            ->setDescription('Generate Recurring');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helperCommon = $this->getContainer()->get('app_admin.helper.common');
        $em           = $this->getContainer()->get('doctrine')->getEntityManager();
        $today        = new \DateTime();
        $subdomain    = $this->getContainer()->getParameter('client_subdomain');
        $config       = $this->getContainer()->get('app_admin.helper.common')->getConfig();

        /* Generate Recurring Invoices */
        $query = $this->getContainer()->get('doctrine')->getEntityManager()->createQueryBuilder();
        $query->select('p')
            ->from('AppClientBundle:ClientRecurring', 'p')
            ->andWhere('p.nextDue <= :date')
            ->setParameter('date', $today->add(new \DateInterval('P'.$config->getGenerateInvoice().'D')))
        ;
        $recurrings = $query->getQuery()->getResult();
        $data['invoices'] = 0;

        foreach ($recurrings as $recurring) {
            $data['invoices']++;
            
            $recurringItems = $this->getContainer()->get('doctrine')->getRepository('AppClientBundle:ClientRecurringItem')->findBy(array(
                'idRecurring' => $recurring->getId()
            ));
            $number = '';

            // Generate invoice
            $invoice = new Entity\ClientInvoice();
            $invoice->setIdClient($recurring->getIdClient());
            $invoice->setSubject(is_null($recurring->getSubject()) ? 'Untitled Invoice' : $recurring->getSubject());
            $invoice->setIssueDate(new \DateTime);
            $invoice->setDiscount($recurring->getDiscount());
            $invoice->setTax($recurring->getTax());
            $invoice->setDueDate($recurring->getNextDue());
            $invoice->setNotes($recurring->getNotes());
            $invoice->setOverdueNotices($recurring->getOverdueNotices());
            $invoice->setReminders($recurring->getReminders());
            $invoice->setStatus(0);
            $invoice->setIdRecurring($recurring->getId());

            // Proforma stuff
            $proformaEnabled = $config->getIsProformaInvoiceEnabled();
            if ($proformaEnabled) {
                $invoice->setStatus(ClientInvoice::STATUS_PROFORMA);
                $prefix = $config->getProformaInvoicePrefix();
                $count = $config->getCountProformaInvoice();

                $number = Business\Invoice\Utils::beautifyId($count, $prefix);
                $invoice->setNumber($number);

                $count++;
                $config->setCountProformaInvoice($count);

                $em->persist($config);
                $em->flush();
            }
            $em->persist($invoice);
            $invoice->setInvoiceAccessToken($helperCommon->generateRandString(16));
            $em->persist($invoice);
            $em->flush();

            if ($number == '') Business\Invoice\Utils::updateInvoicePrefix($this->getContainer(), $invoice->getId());

            // Generate invoice items
            foreach ($recurringItems as $item) {
                $invoiceItem = new Entity\ClientInvoiceItem();
                $invoiceItem->setIdType($item->getIdType());
                $invoiceItem->setDescription($item->getDescription());
                $invoiceItem->setQuantity($item->getQuantity());
                $invoiceItem->setUnitPrice($item->getUnitPrice());
                $invoiceItem->setIdInvoice($invoice->getId());
                $em->persist($invoiceItem);
            }
            $em->flush();

            Business\Invoice\Utils::updateInvoiceStatus($this->getContainer(), $invoice->getId());
            Business\Invoice\Utils::sendInvoiceEmail($this->getContainer(), $invoice, 'invoice');

            $output->writeln("Generated invoice no. " . $invoice->getNumber());

            // Update recurring
            $intervals = array(
                RecurringConstants::SCHEDULE_DAILY         => 'P1D',
                RecurringConstants::SCHEDULE_WEEKLY        => 'P1W',
                RecurringConstants::SCHEDULE_FORTNIGHTLY   => 'P2W',
                RecurringConstants::SCHEDULE_MONTHLY       => 'P1M',
                RecurringConstants::SCHEDULE_QUARTLY       => 'P3M',
                RecurringConstants::SCHEDULE_SEMI_ANNUALLY => 'P6M',
                RecurringConstants::SCHEDULE_ANNUALLY      => 'P1Y',
                RecurringConstants::SCHEDULE_BIENNIALY     => 'P2Y',
                RecurringConstants::SCHEDULE_TRIENNIALY    => 'P3Y'
            );

            if (isset($intervals[$recurring->getIdSchedule()])) {
                $dateInterval = new \DateInterval($intervals[$recurring->getIdSchedule()]);
                $nextDue      = clone $recurring->getNextDue();
                $nextDue->add($dateInterval);
                $recurring->setNextDue($nextDue);
                $em->persist($recurring);
                $em->flush();
            }
        }

        /* Generate Product Invoices */
        $data['clientProducts'] = 0;
        $query = $this->getContainer()->get('doctrine')->getEntityManager()->createQueryBuilder();
        $query->select('p.cost, p.nextDue, p.idSchedule, p.taxGroup, p.overdueNotices, p.reminders, r.name')
            ->from('AppClientBundle:ClientProduct', 'p')
            ->innerJoin('AppClientBundle:Product', 'r', 'WITH', 'p.idProduct = r.id')
            ->andWhere('p.nextDue <= :date')
            ->andWhere('p.status = :activeStatus')
            ->setParameter('date', $today->add(new \DateInterval('P'.$config->getGenerateInvoice().'D')))
            ->setParameter('activeStatus', Business\ClientProduct\Constants::STATUS_ACTIVE)
        ;
        $clientProducts = $query->getQuery()->getResult();

        foreach ($clientProducts as $clientProduct) {
            if($clientProduct->getStatus() == CPConstants::STATUS_ACTIVE
                && $clientProduct->getCost() > 0)
            {
                $data['clientProducts']++;

                // Generate invoice
                $invoice = new Entity\ClientInvoice();
                $invoice->setIdClient($clientProduct->getIdClient());
                $invoice->setSubject(is_null($clientProduct->getName()) ? 'Untitled Invoice' : $clientProduct->getName());
                $invoice->setIssueDate(new \DateTime);
                $invoice->setDiscount(0);
                $invoice->setTax($clientProduct->getTaxGroup());
                $invoice->setDueDate($clientProduct->getNextDue());
                $invoice->setNotes($config->getInvoiceNotes());
                $invoice->setOverdueNotices(1);
                $invoice->setReminders(1);
                $invoice->setStatus(0);
                $invoice->setIdProduct($clientProduct->getIdProduct());

                // Proforma stuff
                $proformaEnabled = $config->getIsProformaInvoiceEnabled();
                if ($proformaEnabled) {
                    $invoice->setStatus(ClientInvoice::STATUS_PROFORMA);
                    $prefix = $config->getProformaInvoicePrefix();
                    $count = $config->getCountProformaInvoice();

                    $number = Business\Invoice\Utils::beautifyId($count, $prefix);
                    $invoice->setNumber($number);

                    $count++;
                    $config->setCountProformaInvoice($count);

                    $this->entityManager->persist($config);
                    $this->entityManager->flush();
                }
                $em->persist($invoice);
                $invoice->setInvoiceAccessToken($helperCommon->generateRandString(16));
                $em->persist($invoice);
                $em->flush();

                if ($number == '') Business\Invoice\Utils::updateInvoicePrefix($this->getContainer(), $invoice->getId());

                // Generate invoice items
                $invoiceItem = new Entity\ClientInvoiceItem();
                $invoiceItem->setIdType(Business\InvoiceItem\Constants::TYPE_HOSTING);
                $invoiceItem->setDescription($clientProduct->getName());
                $invoiceItem->setQuantity(1);
                $invoiceItem->setUnitPrice($clientProduct->getCost());
                $invoiceItem->setIdInvoice($invoice->getId());
                $em->persist($invoiceItem);
                $em->flush();

                Business\Invoice\Utils::updateInvoiceStatus($this->getContainer(), $invoice->getId());
                Business\Invoice\Utils::sendInvoiceEmail($this->getContainer(), $invoice, 'invoice');

                $output->writeln("Generated invoice no. " . $invoice->getNumber());

                // Update recurring
                $intervals = array(
                    OrderConstants::SCHEDULE_MONTHLY         => 'P1M',
                    OrderConstants::SCHEDULE_QUARTERLY       => 'P3M',
                    OrderConstants::SCHEDULE_SEMI_ANNUALLY   => 'P6M',
                    OrderConstants::SCHEDULE_ANNUALLY        => 'P1Y',
                    OrderConstants::SCHEDULE_BIENNIALLY      => 'P2Y',
                    OrderConstants::SCHEDULE_TRIENNIALLY     => 'P3Y'
                );

                if (isset($intervals[$clientProduct->getIdSchedule()])) {
                    $dateInterval = new \DateInterval($intervals[$clientProduct->getIdSchedule()]);
                    $nextDue      = clone $clientProduct->getNextDue();
                    $nextDue->add($dateInterval);
                    $clientProduct->setNextDue($nextDue);
                    $em->persist($clientProduct);
                    $em->flush();
                }
            }
        }


        /* Send Emails */
        $query = $this->getContainer()->get('doctrine')->getEntityManager()->createQueryBuilder();
        $query->select('p')
            ->from('AppClientBundle:ClientInvoice', 'p')
            ->andWhere('p.status != :paid')
            ->andWhere('p.status != :writtenoff')
            ->setParameter('paid', InvoiceConstants::STATUS_PAID)
            ->setParameter('writtenoff', InvoiceConstants::STATUS_WRITTEN_OFF)
        ;
        $result = $query->getQuery()->getResult();

        // Logging for daily summary
        $summary['reminders']     = 0;
        $summary['overdues']      = 0;
        $summary['suspensions']   = 0;
        $summary['terminations']  = 0;

        // Send emails
        foreach($result as $invoice)
        {
            // Update invoice status
            $output->writeln("Updating status for invoice: ".$invoice->getNumber());
            Business\Invoice\Utils::updateInvoiceStatus($this->getContainer(), $invoice->getId());

            // Invoices with no due date set break this
            if(is_null($invoice->getDueDate())) {
                $output->writeln("Ignoring invoice because it has no due date: ".$invoice->getNumber());
                continue;
            }

            // If it's not overdue
            if($invoice->getDueDate()->diff($today)->format("%r%a") > 0)
            {
                // Send a reminder if applicable
                $diff = $today->diff($invoice->getDueDate())->format("%a");
                if(in_array($diff, $config->getSendReminder())){
                    Business\Invoice\Utils::sendInvoiceEmail($this->getContainer(), $invoice, 'reminder');
                    $output->writeln("Emailing reminder for invoice " . $invoice->getNumber());
                    $summary['reminders']++;
                }

            // If it is overdue
            } else {
                // Send an overdue notice if applicable
                $diff = $invoice->getDueDate()->diff($today)->format("%a");
                if(in_array($diff, $config->getSendOverdue())){
                    Business\Invoice\Utils::sendInvoiceEmail($this->getContainer(), $invoice, 'overdue');
                    $output->writeln("Emailing overdue notice for invoice " . $invoice->getNumber());
                    $summary['overdues']++;
                }

                // Suspend if applicable
                $diff = $invoice->getDueDate()->diff($today)->format("%a");
                if($diff >= $config->getSuspendAfter() && $invoice->getIdProduct() && $config->getSuspendAfter()){
                    $cp = $this->getContainer()->get('doctrine')->getRepository('AppClientBundle:ClientProduct')->findOneById($invoice->getIdClientProduct());
                    if($this->getContainer()->get('app_admin.helper.automation_helper')->suspendClientProduct($cp))
                    {
                        $output->writeln("Suspending client product " . $cp->getId());
                        $summary['suspensions']++;
                    }
                }

                // Terminate if applicable
                if($diff >= $config->getTerminateAfter() && $invoice->getIdProduct() && $config->getTerminated()){
                    $cp = $this->getContainer()->get('doctrine')->getRepository('AppClientBundle:ClientProduct')->findOneById($invoice->getIdClientProduct());
                    if($this->getContainer()->get('app_admin.helper.automation_helper')->terminateClientProduct($cp))
                    {
                        $output->writeln("Terminating client product " . $cp->getId());
                        $summary['terminations']++;
                    }
                }
            }
        }

        // If we are sending the daily summary, send it now
        if($config->getDailySummary()){
            $tenantOwner = $helperCommon->getCurrentTenant()->getUser();
            $senderEmail = $config->getDefaultEmail();
            $senderName  = $config->getBusinessName();
            $content     = $this->getContainer()->get('templating')->render('AppAdminBundle:Cron:summary_email.html.twig', $data);

            $message     = \Swift_Message::newInstance()
                ->setSubject('Loading Deck Daily Summary')
                ->setFrom($senderEmail, $senderName)
                ->setTo($tenantOwner->getEmail())
                ->setBody($content, 'text/html');
            $this->getContainer()->get('mailer')->send($message);
        }

        // Finish by flushing the mail queue
        $spool = $this->getContainer()->get('mailer')->getTransport()->getSpool();
        $transport = $this->getContainer()->get('swiftmailer.transport.real');
        $spool->flushQueue($transport);
    }
}