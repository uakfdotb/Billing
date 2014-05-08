<?php

namespace App\AdminBundle\Business\PaymentGateway;

use App\AdminBundle\Business;
use App\AdminBundle\Business\Base\BaseGridHandler;

class GridHandler extends BaseGridHandler
{
    public function buildBaseQuery($query, $baseObject = 'g', $filter)
    {
        $query->select($baseObject)
            ->from('AppPaymentsBundle:PaymentGateway', $baseObject);

        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }


    public function postParseRow(&$r)
    {
        /*$r['id']   = $this->helperFormatter->format($r['id'],   'mapping', 'id');
        $r['name'] = $this->helperFormatter->format($r['name'], 'mapping', 'name');
        $r['type'] = $this->helperFormatter->format($r['type'], 'mapping', 'type');*/
    }

}