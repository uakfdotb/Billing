<?php

namespace App\AdminBundle\Business\ClientEmail;

use App\AdminBundle\Business\Base\BaseGridHandler;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $idClient = $this->container->get('request')->query->get('id', 0);
        $query->select($baseObject)
            ->from('AppClientBundle:ClientEmail', $baseObject)
            ->andWhere($baseObject . '.id_client = :idClient')
            ->setParameter('idClient', $idClient);
        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }

    public function postParseRow(&$r)
    {
        $r['timestamp'] = $this->helperFormatter->format($r['timestamp'], 'datetime');
        $r['idClient']  = $this->helperMapping->getMappingTitle('client_list', $r['idClient']);
    }
}