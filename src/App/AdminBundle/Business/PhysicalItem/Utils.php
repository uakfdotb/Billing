<?php
namespace App\AdminBundle\Business\PhysicalItem;

use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class Utils
{
    public static function makeInvoice($controller, $idPhysicalItem, $soldItemArray)
    {
        $config    = $controller->get('app_admin.helper.common')->getConfig();
        $em        = $controller->getDoctrine()->getEntityManager();
        $formatter = $controller->get('app_admin.helper.formatter');

        $physicalItem  = $controller->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:PhysicalItem');
        $helperMapping = $controller->get('app_admin.helper.mapping');

        if (empty($soldItemArray)) $soldItemArray[] = 0;
        $invoices = array();
        $query    = $em->createQueryBuilder();
        $query->select('p')
            ->from('AppClientBundle:PhysicalItemSold', 'p')
            ->andWhere('p.idPhysicalItem = :idPhysicalItem')
            ->andWhere('p.id in (:itemArray)')
            ->andWhere('p.invoiced = 0')
            ->setParameter('idPhysicalItem', $idPhysicalItem)
            ->setParameter('itemArray', $soldItemArray);
        $result = $query->getQuery()->getResult();
        if (!empty($result)) {
            foreach ($result as $r) {
                if (!isset($invoices[$r->getIdClient()])) {
                    $invoices[$r->getIdClient()] = array();
                }
                $invoices[$r->getIdClient()][] = $r;
            }
        }

        if (!empty($invoices)) {
            foreach ($invoices as $idClient => $invoiceItems) {
                $discount      = $config->getDefaultDiscount();
                $tax           = $config->getDefaultTax();
                $defaultIdType = $config->getIdDefaultWorkType();

                $itemName = $physicalItem->getName() . '/' . $physicalItem->getBrand() . '/' . $physicalItem->getModel();

                $invoice = new Entity\ClientInvoice();
                $invoice->setIdClient($idClient);
                $invoice->setSubject('Sold: ' . $itemName);
                $invoice->setIssueDate(new \DateTime());
                $invoice->setDueDate(new \DateTime());
                $invoice->setDiscount($discount);
                $invoice->setTax($tax);
                $em->persist($invoice);
                $em->flush();

                foreach ($invoiceItems as $item) {
                    $quantity    = $item->getQuantity();
                    $unitPrice   = $item->getSellPrice();
                    $description = $itemName . '/' . $physicalItem->getDescription() . ' (SN: ' . $item->getSerialNumber() . ')';

                    $invoiceItem = new Entity\ClientInvoiceItem();
                    $invoiceItem->setIdType($defaultIdType);
                    $invoiceItem->setDescription($description);
                    $invoiceItem->setQuantity($quantity);
                    $invoiceItem->setUnitPrice($unitPrice);
                    $invoiceItem->setIdInvoice($invoice->getId());
                    $em->persist($invoiceItem);

                    $item->setInvoiced(1);
                    $em->persist($item);
                    $em->flush();
                }

                Business\Invoice\Utils::updateInvoicePrefix($controller, $invoice->getId());
                Business\Invoice\Utils::updateInvoiceStatus($controller, $invoice->getId());
            }
            return true;
        }

        return false;
    }


    public static function addPurchase($controller, $idPhysicalItem, $purchaseItemArray)
    {
        $config    = $controller->get('app_admin.helper.common')->getConfig();
        $em        = $controller->getDoctrine()->getEntityManager();
        $formatter = $controller->get('app_admin.helper.formatter');

        $physicalItem  = $controller->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:PhysicalItem');
        $helperMapping = $controller->get('app_admin.helper.mapping');

        if (empty($purchaseItemArray)) $purchaseItemArray[] = 0;
        $query = $em->createQueryBuilder();
        $query->select('p')
            ->from('AppClientBundle:PhysicalItemPurchase', 'p')
            ->andWhere('p.idPhysicalItem = :idPhysicalItem')
            ->andWhere('p.id in (:itemArray)')
            ->andWhere('p.isPurchased = 0')
            ->setParameter('idPhysicalItem', $idPhysicalItem)
            ->setParameter('itemArray', $purchaseItemArray);
        $result = $query->getQuery()->getResult();
        if ($result) {
            foreach ($result as $r) {
                $amount = $r->getPurchasePrice() * $r->getQuantity();
                $name   = $physicalItem->getName() . ' (SN:' . $r->getSerialNumber() . ')';

                $purchase = new Entity\SupplierPurchase();
                $purchase->setIdSupplier($r->getIdSupplier());
                $purchase->setName($name);
                $purchase->setPurchaseDate($r->getDateIn());
                $purchase->setIdAccountTransaction(0);
                $purchase->setAmount($amount);
                $em->persist($purchase);

                $r->setIsPurchased(1);
                $em->persist($r);
                $em->flush();
            }

            return true;
        }

        return false;
    }
}