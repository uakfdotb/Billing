<?php
namespace App\AdminBundle\Business\PhysicalItemPurchase;

use App\AdminBundle\Business\Base\BaseGridHandler;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $id = $this->container->get('request')->get('id', 0);
        $query->select($baseObject)
            ->from('AppClientBundle:PhysicalItemPurchase', $baseObject)
            ->andWhere($baseObject . '.idPhysicalItem = :idPhysicalItem')
            ->setParameter('idPhysicalItem', $id);

        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }

    public function postParseRow(&$r)
    {
        $r['dateIn'] = $this->helperFormatter->format($r['dateIn'], 'datetime');
    }
}