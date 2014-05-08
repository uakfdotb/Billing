<?php

namespace App\AdminBundle\Business\Order;

use App\AdminBundle\Business;
use App\ClientBundle\Entity;

class Utils
{
    public static function generateInvoice($container, $idClient, $idProduct, $idOrderPaymentTerm, $orderNumber, &$invoice)
    {
        $doctrine = $container->get('doctrine');
        $em       = $doctrine->getEntityManager();
        $helper   = $container->get('app_admin.helper.common');
        $config   = $helper->getConfig();

        // Create estimate
        $product  = $doctrine->getRepository('AppClientBundle:Product')->findOneById($idProduct);
        $setupFee = 0;
        $price    = 0;
        switch ($idOrderPaymentTerm) {
            case Constants::SCHEDULE_MONTHLY:
                $setupFee = $product->getSetupFeeMonthly();
                $price    = $product->getPriceMonthly();
                break;
            case Constants::SCHEDULE_QUARTERLY:
                $setupFee = $product->getSetupFeeQuarterly();
                $price    = $product->getPriceQuarterly();
                break;
            case Constants::SCHEDULE_SEMI_ANNUALLY:
                $setupFee = $product->getSetupFeeSemiAnnually();
                $price    = $product->getPriceSemiAnnually();
                break;
            case Constants::SCHEDULE_ANNUALLY:
                $setupFee = $product->getSetupFeeAnnually();
                $price    = $product->getPriceAnnually();
                break;
            case Constants::SCHEDULE_BIENNIALLY:
                $setupFee = $product->getSetupFeeBiennially();
                $price    = $product->getPriceBiennially();
                break;
            case Constants::SCHEDULE_TRIENNIALLY:
                $setupFee = $product->getSetupFeeTriennially();
                $price    = $product->getPriceTriennially();
                break;
        }
        if ($setupFee < 0) {
            $setupFee = 0;
        }
        if ($price < 0) {
            $price = 0;
        }
        if($price > 0 || $setupFee > 0)
        {
            $invoice = new Entity\ClientInvoice();
            $invoice->setIdClient($idClient);
            $invoice->setSubject('Invoice for order ' . $orderNumber);
            $invoice->setIssueDate(new \DateTime());
            $invoice->setDiscount(0);
            $invoice->setTax($product->getTaxGroup());
            $invoice->setIdProduct($product->getId());
            $invoice->setInvoiceAccessToken($helper->generateRandString(16));
            $invoice->setNotes($config->getInvoiceNotes());
            $invoice->setDueDate(new \DateTime());
            $em->persist($invoice);
            $em->flush();

            $invoiceItem = new Entity\ClientInvoiceItem();
            $invoiceItem->setIdInvoice($invoice->getId());
            $invoiceItem->setIdType(Business\InvoiceItem\Constants::TYPE_HOSTING);
            $invoiceItem->setDescription('Setup fee');
            $invoiceItem->setQuantity(1);
            $invoiceItem->setUnitPrice($setupFee ?: 0);
            $em->persist($invoiceItem);

            $invoiceItem = new Entity\ClientInvoiceItem();
            $invoiceItem->setIdInvoice($invoice->getId());
            $invoiceItem->setIdType($product->getIdType() ?: 0);
            $invoiceItem->setDescription('Recurring Price');
            $invoiceItem->setQuantity(1);
            $invoiceItem->setUnitPrice($price ?: 0);
            $em->persist($invoiceItem);
            $em->flush();

            Business\Invoice\Utils::updateInvoicePrefix($container, $invoice->getId());
            Business\Invoice\Utils::updateInvoiceStatus($container, $invoice->getId());

            return $invoice->getId();
        }
        return null;
    }

    public static function checkMaxMind($container, $idClient)
    {
        $doctrine = $container->get('doctrine');
        $config = $container->get('app_admin.helper.common')->getConfig();
        $ccfs = $container->get('app_admin.helper.credit_card_fraud_detection');

        $client = Business\GlobalUtils::getClientById($container, $idClient);
        $country = $container->get('doctrine')->getRepository('AppClientBundle:Country')->findOneById($client->getIdCountry());

        $h = array();
        $h["license_key"] = $config->getMaxmindLicenseKey();
        $h["i"] = $_SERVER['REMOTE_ADDR'];
        $h["city"] = $client->getCity();
        $h["region"] = $client->getState();
        $h["postal"] = $client->getPostcode();
        $h["country"] = empty($country) ? '' : $country->getCode();
        $h['emailMD5'] = md5($client->getEmail());
        $h["user_agent"] = $_SERVER['HTTP_USER_AGENT'];

        $raw = '';
        $result = $ccfs->query($h, $raw);
        return array('result' => $result, 'raw' => $raw);
    }

    public static function decreaseProductStock($container, $idProduct)
    {
        $product = $container->get('doctrine')->getRepository('AppClientBundle:Product')->findOneById($idProduct);
        if ($product) {
            $em = $container->get('doctrine')->getEntityManager();
            $product->setStock($product->getStock() - 1);
            $em->persist($product);
            $em->flush();
        }
    }

    public static function getPaymentTerms($product)
    {
        $terms = array();
        if ($product->getSetupFeeMonthly() >= 0 && $product->getPriceMonthly() >= 0) {
            $terms[Constants::SCHEDULE_MONTHLY] = 'Monthly';
        }
        if ($product->getSetupFeeQuarterly() >= 0 && $product->getPriceQuarterly() >= 0) {
            $terms[Constants::SCHEDULE_QUARTERLY] = 'Quarterly';
        }
        if ($product->getSetupFeeSemiAnnually() >= 0 && $product->getPriceSemiAnnually() >= 0) {
            $terms[Constants::SCHEDULE_SEMI_ANNUALLY] = 'Semi-annually';
        }
        if ($product->getSetupFeeAnnually() >= 0 && $product->getPriceAnnually() >= 0) {
            $terms[Constants::SCHEDULE_ANNUALLY] = 'Annually';
        }
        if ($product->getSetupFeeBiennially() >= 0 && $product->getPriceBiennially() >= 0) {
            $terms[Constants::SCHEDULE_BIENNIALLY] = 'Biennially';
        }
        if ($product->getSetupFeeTriennially() >= 0 && $product->getPriceTriennially() >= 0) {
            $terms[Constants::SCHEDULE_TRIENNIALLY] = 'Triennially';
        }

        return $terms;
    }

    public static function sendConfirmation($container, $order)
    {
        // Get order information
        $doctrine     = $container->get('doctrine');
        $client       = Business\GlobalUtils::getClientById($container, $order->getIdClient());
        $config       = $container->get('app_admin.helper.common')->getConfig();
        $businessName = $config->getBusinessName();
        $senderEmail  = $container->get('service_container')->getParameter('email_address');
        $senderName   = $container->get('service_container')->getParameter('email_sender_name');
        $content      = $config->getOrderEmail();
        $contacts     = Business\ClientContact\Utils::getContactEmailsByType($container, $order->getIdClient(), 'billing');
        $subject      = "Order Confirmation From ".$businessName;

        $replacements = [
            "{client_first_name}" => $client->getFirstName(),
            "{client_last_name}"  => $client->getLastName(),
            "{client_full_name}"  => $client->getFirstName() . ' ' . $client->getLastName(),
            "{order_number}"      => $order->getOrderNumber(),
            "{business_name}"     => $businessName
        ];

        foreach($replacements as $key => $value)
            $content = str_replace($key, $value, $content);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($senderEmail, $senderName)
            ->setTo($client->getEmail())
            ->setBody(nl2br($content), 'text/html')
            ->setReplyTo(array($config->getDefaultEmail() => $config->getBusinessName()));

        foreach($contacts as $contact) $message->addCC($contact);

        $container->get('mailer')->send($message);

        // Log email
        $clientEmail = new Entity\ClientEmail();
        $clientEmail->setIdClient($client->getId());
        $clientEmail->setTimestamp(new \DateTime());
        $clientEmail->setSubject($subject);
        $doctrine->getManager()->persist($clientEmail);
        $doctrine->getManager()->flush();
    }

}

