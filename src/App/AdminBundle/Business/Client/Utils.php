<?php
namespace App\AdminBundle\Business\Client;

use App\AdminBundle\Business;
use App\AdminBundle\Business\Invoice\Constants as InvoiceConstants;
use App\AdminBundle\Business\Recurring\Constants as RecurringConstants;

class Utils
{
    public static function getClientName($container, $client)
    {
        $name = $client->getFirstName() . ' ' . $client->getLastName();
        if ($client->getCompany() != null) {
            $name .= ' (' . $client->getCompany() . ')';
        }
        return $name;
    }

    public static function parseCsv($container, $fileName)
    {
        $validator = $container->get('app_admin.business.client.import_validator');
        $validator->initialize();

        $keywords  = array('firstname', 'lastname', 'company', 'address1', 'address2', 'city', 'state', 'postcode', 'country', 'phone', 'email', 'password');
        $csvParser = $container->get('app_admin.helper.csv_parser');
        $errors    = array();
        $array     = $csvParser->parseFile($fileName, $keywords, $validator, $errors);
        return array(
            'result' => $array,
            'errors' => $errors
        );
    }

    public static function computeBalance($container, $idClient)
    {
        $doctrine = $container->get('doctrine');

        $balance  = 0;
        foreach([Business\Invoice\Constants::STATUS_UNPAID, Business\Invoice\Constants::STATUS_PROFORMA] as $status)
        {
            $filters  = array(
                'idClient' => $idClient,
                'status'   => $status
            );
            $invoices = $doctrine->getRepository('AppClientBundle:ClientInvoice')->findBy($filters);
            foreach ($invoices as $i) {
                $balance += $i->getTotalPayment() - $i->getTotalAmount();
            }
        }

        $creditNotes = $doctrine->getRepository('AppClientBundle:ClientCreditNote')->findByIdClient($idClient);
        foreach ($creditNotes as $c) {
            $balance += $c->getAmount();
        }

        return $balance;
    }
    public static function getNumberOf($container, $item, $idClient)
    {
        $items = $container->get('doctrine')->getRepository('AppClientBundle:'.$item)->findBy(['idClient' => $idClient]);
        return count($items);
    }
    public static function getTotalIncome($container, $idClient)
    {
        $items = $container->get('doctrine')->getRepository('AppClientBundle:ClientInvoice')->findBy([
            'idClient' => $idClient,
            'status'   => InvoiceConstants::STATUS_PAID
        ]);

        $total = 0;
        foreach($items as $item) $total += $item->getTotalAmount();

        return $total;
    }
    public static function getSignupDate($container, $idClient)
    {
        return $container->get('doctrine')->getRepository('AppUserBundle:User')->findOneBy(['id' => $idClient])->getCreatedAt();
    }

    public static function getStatusFromId($id)
    {
        $array = [
            "" => "Select Status",
            "1" => "Active",
            "0" => "Inactive"
        ];
        foreach($array as $key => $value)
        {
            if($key == $id) return $value;
        }
        return "";
    }
}