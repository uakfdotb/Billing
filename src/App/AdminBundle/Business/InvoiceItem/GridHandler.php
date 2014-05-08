<?php

namespace App\AdminBundle\Business\InvoiceItem;
use App\AdminBundle\Business\Base\BaseGridHandler;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $query->select($baseObject)
            ->from('AppClientBundle:ClientInvoiceItem', $baseObject);
        $idInvoice = $this->container->get('request')->query->get('id', 0);
        $query->andWhere($baseObject . '.idInvoice = :idInvoice')
            ->setParameter('idInvoice', $idInvoice);

        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }

    public function postParseRow(&$r)
    {
        $r['total'] = floatval($r['quantity']) * floatval($r['unitPrice']);
    }
}