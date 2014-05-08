<?php
namespace App\AdminBundle\Business\Client;

use App\AdminBundle\Business\Base\BaseGridHandler;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $query->select($baseObject)
            ->from('AppUserBundle:User', $baseObject)
        ;

        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }

    public function postParseRow(&$r)
    {
        $r['balance'] = Utils::computeBalance($this->container, $r['id']);
        $r['balance'] = $this->helperFormatter->format($r['balance'], 'money');
    }
}