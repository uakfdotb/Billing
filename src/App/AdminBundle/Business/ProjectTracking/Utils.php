<?php
namespace App\AdminBundle\Business\ProjectTracking;

use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class Utils
{
    public static function bill($controller, $idProject, $trackingItemArray)
    {
        $config = $controller->get('app_admin.helper.common')->getConfig();
        $em     = $controller->getDoctrine()->getEntityManager();

        $project       = $controller->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ClientProject');
        $helperMapping = $controller->get('app_admin.helper.mapping');

        if (empty($trackingItemArray)) $trackingItemArray[] = 0;
        $query = $em->createQueryBuilder();
        $query->select('pt')
            ->from('AppClientBundle:ClientProjectTracking', 'pt')
            ->andWhere('pt.idProject = :idProject')
            ->andWhere('pt.id in (:itemArray)')
            ->andWhere('pt.invoiced = 0')
            ->andWhere('pt.stop is not NULL')
            ->setParameter('idProject', $idProject)
            ->setParameter('itemArray', $trackingItemArray);
        $result = $query->getQuery()->getResult();
        if (!empty($result)) {
            $discount      = floatval($config->getDefaultDiscount());
            $tax           = floatval($config->getDefaultTax());
            $defaultIdType = $config->getIdDefaultWorkType();

            $invoice = new Entity\ClientInvoice();
            $invoice->setIdClient($project->getIdClient());
            $invoice->setSubject($project->getName());
            $invoice->setIssueDate(new \DateTime());
            $invoice->setDueDate(new \DateTime());
            $invoice->setDiscount($discount);
            $invoice->setTax($tax);
            $em->persist($invoice);
            $em->flush();

            foreach ($result as $projectTracking) {
                $quantity = ($projectTracking->getStop()->getTimestamp() - $projectTracking->getStart()->getTimestamp()) / 3600;
                $hours    = number_format($quantity, 4);

                $invoiceItem = new Entity\ClientInvoiceItem();
                $invoiceItem->setIdType($defaultIdType);
                $invoiceItem->setDescription('Hourly rate for ' . $helperMapping->getMappingTitle('staff_list', $projectTracking));
                $invoiceItem->setQuantity($hours);
                $invoiceItem->setUnitPrice($projectTracking->getHourly());
                $invoiceItem->setIdInvoice($invoice->getId());
                $em->persist($invoiceItem);

                $projectTracking->setInvoiced(1);
                $em->persist($projectTracking);
            }
            $em->flush();

            Business\Invoice\Utils::updateInvoicePrefix($controller, $invoice->getId());
            Business\Invoice\Utils::updateInvoiceStatus($controller, $invoice->getId());

            return true;
        }
        return false;
    }
}