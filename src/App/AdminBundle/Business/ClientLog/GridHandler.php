<?php

namespace App\AdminBundle\Business\ClientLog;

use App\AdminBundle\Business\Base\BaseGridHandler;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $query->select($baseObject)
            ->from('AppClientBundle:ClientLog', $baseObject);
        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }

    public function postParseRow(&$r)
    {
        $r['timestamp'] = $this->helperFormatter->format($r['timestamp'], 'datetime');
        $r['idType']    = $this->helperMapping->getMappingTitle('client_log_type', $r['idType']);
        $r['idClient']  = $this->helperMapping->getMappingTitle('client_list', $r['idClient']);
    }
}