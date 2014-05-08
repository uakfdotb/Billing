<?php

namespace App\AdminBundle\Business\SupplierPurchase;


use App\AdminBundle\Business\Base\BaseGridHandler;


class GridHandler extends BaseGridHandler
{

    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {

        $query->select($baseObject . ', t.amount as tAmount')

            ->from('AppClientBundle:SupplierPurchase', $baseObject)

            ->leftJoin('AppClientBundle:AccountTransaction', 't', 'WITH', 't.id = ' . $baseObject . '.idAccountTransaction');


        // Build filter here - Consult arrayToSQL($filter, false)

        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);

    }


    public function parseRow($row)
    {

        $arr = $this->helperCommon->copyEntityToArray($row[0]);

        unset($row[0]);

        return array_merge($arr, $row);

    }


    public function postParseRow(&$r)
    {

        $url = $this->container->get('router')->generate('app_admin_supplier_purchase_download');

        $attachments = $this->container->get('app_admin.helper.doctrine')->loadList('AppClientBundle:SupplierPurchaseFile', 'idPurchase', 'id', $r['id']);

        $r['attachments'] = array();

        foreach ($attachments as $file) {

            $r['attachments'][] = '<a target="_blank" href="' . $url . '?id=' . $file . '">' . $file . '</a>';

        }

        $r['attachments'] = implode('&nbsp;&nbsp;', $r['attachments']);


        $r['supplier'] = $this->helperFormatter->format($r['idSupplier'], 'mapping', 'supplier_list');

        $r['purchaseDate'] = $this->helperFormatter->format($r['purchaseDate'], 'date');

        if (empty($r['idAccountTransaction'])) {
            $r['amount'] = $this->helperFormatter->format($r['amount'], 'money');
        } else {
            $r['amount'] = $this->helperFormatter->format($r['tAmount'], 'money');
        }
    }

}

