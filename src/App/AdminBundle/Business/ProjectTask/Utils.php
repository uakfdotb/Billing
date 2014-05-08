<?php
namespace App\AdminBundle\Business\ProjectTask;

use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class Utils
{
    public static function bill($controller, $idProject, $taskItemArray, $idInvoice)
    {
        $config    = $controller->get('app_admin.helper.common')->getConfig();
        $em        = $controller->getDoctrine()->getEntityManager();
        $formatter = $controller->get('app_admin.helper.formatter');

        $project       = $controller->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ClientProject');
        $invoice       = $controller->get('app_admin.helper.doctrine')->findOneById('AppClientBundle:ClientInvoice', $idInvoice);
        $helperMapping = $controller->get('app_admin.helper.mapping');

        if (empty($taskItemArray)) $taskItemArray[] = 0;
        $query = $em->createQueryBuilder();
        $query->select('pt')
            ->from('AppClientBundle:ClientProjectTask', 'pt')
            ->andWhere('pt.idProject = :idProject')
            ->andWhere('pt.id in (:itemArray)')
            ->andWhere('pt.invoiced = 0')
            ->setParameter('idProject', $idProject)
            ->setParameter('itemArray', $taskItemArray);
        $result = $query->getQuery()->getResult();
        if (!empty($result)) {
            $discount      = $config->getDefaultDiscount();
            $tax           = $config->getDefaultTax();
            $defaultIdType = $config->getIdDefaultWorkType();

            if (empty($invoice)) {
                $invoice = new Entity\ClientInvoice();
                $invoice->setIdClient($project->getIdClient());
                $invoice->setSubject($project->getName());
                $invoice->setIssueDate(new \DateTime());
                $invoice->setDueDate(new \DateTime());
                $invoice->setDiscount($discount);
                $invoice->setTax($tax);
                $em->persist($invoice);
                $em->flush();
            }

            foreach ($result as $projectTask) {
                $quantity    = $projectTask->getQuantity();
                $unitPrice   = $projectTask->getUnitPrice();
                $description = $formatter->format($projectTask->getTimestamp(), 'date') . ' - ' . $projectTask->getSubject();

                $invoiceItem = new Entity\ClientInvoiceItem();
                $invoiceItem->setIdType($projectTask->getIdWorkType());
                $invoiceItem->setDescription($description);
                $invoiceItem->setQuantity($quantity);
                $invoiceItem->setUnitPrice($unitPrice);
                $invoiceItem->setIdInvoice($invoice->getId());
                $em->persist($invoiceItem);

                $projectTask->setInvoiced(1);
                $projectTask->setStatus(Constants::STATUS_INVOICED);
                $em->persist($projectTask);
            }
            $em->flush();

            Business\Invoice\Utils::updateInvoicePrefix($controller, $invoice->getId());
            Business\Invoice\Utils::updateInvoiceStatus($controller, $invoice->getId());

            return true;
        }
        return false;
    }
}