<?php

namespace App\AdminBundle\Business\InvoicePayment;

use App\AdminBundle\Business;
use App\ClientBundle\Entity;

class Utils
{
    public static function refund($controller, $idPayment)
    {
        $doctrine = $controller->get('doctrine');
        $em       = $doctrine->getEntityManager();

        $payment = $doctrine->getRepository('AppClientBundle:ClientPayment')->findOneById($idPayment);
        if ($payment) {
            $invoice = $doctrine->getRepository('AppClientBundle:ClientInvoice')->findOneById($payment->getIdInvoice());
            if ($invoice) {
                $refundPayment = new Entity\ClientPayment();
                $refundPayment->setIdGateway($payment->getIdGateway());
                $refundPayment->setTransaction("Refund transaction: " . $payment->getTransaction());
                $refundPayment->setPayDate(new \DateTime());
                $refundPayment->setAmount($payment->getAmount() * -1);
                $refundPayment->setIdInvoice($invoice->getId());
                $refundPayment->setIdType(2);

                $payment->setIdType(1);
                $em->persist($payment);
                $em->persist($refundPayment);
                $em->flush();

                Business\Invoice\Utils::updateInvoiceStatus($controller, $invoice->getId());

                return true;
            }
        }
        return false;
    }
}