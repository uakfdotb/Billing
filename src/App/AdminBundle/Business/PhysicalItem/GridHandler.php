<?php
namespace App\AdminBundle\Business\PhysicalItem;

use App\AdminBundle\Business\Base\BaseGridHandler;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $query->select($baseObject)
            ->from('AppClientBundle:PhysicalItem', $baseObject);

        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }

    public function postParseRow(&$r)
    {

    }
}