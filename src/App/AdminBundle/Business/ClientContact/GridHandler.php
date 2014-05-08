<?php
namespace App\AdminBundle\Business\ClientContact;

use App\AdminBundle\Business\Base\BaseGridHandler;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $idClient = $this->container->get('request')->query->get('id', 0);
        $query->select($baseObject)
            ->from('AppClientBundle:ClientContact', $baseObject)
            ->andWhere($baseObject . '.idClient = :idClient')
            ->setParameter('idClient', $idClient);

        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }
}