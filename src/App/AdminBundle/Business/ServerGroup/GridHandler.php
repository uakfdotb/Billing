<?php
namespace App\AdminBundle\Business\ServerGroup;

use App\AdminBundle\Business\Base\BaseGridHandler;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $query->select($baseObject)
            ->from('AppClientBundle:ServerGroup', $baseObject);

        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }

    public function postParseRow(&$r)
    {
        $r['type'] = Constants::getServerGroupTypes()[$r['type']];
        $r['choiceLogic'] = Constants::getServerGroupLogic()[$r['choiceLogic']];
    }
}