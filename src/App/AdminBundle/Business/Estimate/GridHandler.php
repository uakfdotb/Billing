<?php
namespace App\AdminBundle\Business\Estimate;

use App\AdminBundle\Business\Base\BaseGridHandler;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $query->select($baseObject)
            ->from('AppClientBundle:ClientEstimate', $baseObject);

        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }

    public function postParseRow(&$r)
    {
        $r['idClient'] = $this->helperMapping->getMappingTitle('client_list', $r['idClient']);
        $r['status']   = $this->helperMapping->getMappingTitle('estimate_status', $r['status']);

        $r['amount']  = $r['totalAmount'];
        $r['payment'] = round($r['totalPayment'], 2);


        $r['issueDate'] = $this->helperFormatter->format($r['issueDate'], 'date');
        $r['dueDate']   = $this->helperFormatter->format($r['dueDate'], 'date');
    }
}