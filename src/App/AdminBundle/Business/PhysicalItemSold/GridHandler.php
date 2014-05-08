<?php
namespace App\AdminBundle\Business\PhysicalItemSold;

use App\AdminBundle\Business\Base\BaseGridHandler;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $id = $this->container->get('request')->get('id', 0);
        $query->select($baseObject)
            ->from('AppClientBundle:PhysicalItemSold', $baseObject)
            ->andWhere($baseObject . '.idPhysicalItem = :idPhysicalItem')
            ->setParameter('idPhysicalItem', $id);

        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }

    public function postParseRow(&$r)
    {
        $r['dateOut'] = $this->helperFormatter->format($r['dateOut'], 'datetime');
    }
}