<?php

namespace App\ClientBundle\Business\Estimate;

use App\ClientBundle\Business;
use App\AdminBundle\Business as AdminBusiness;
use App\ClientBundle\Business\Base\BaseGridHandler;
use App\ClientBundle\Entity;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $query->select($baseObject)
            ->from('AppClientBundle:ClientEstimate', $baseObject)
            ->andWhere($baseObject . '.idClient = :idClient')
            ->setParameter('idClient', $this->getUserId());
    }


    public function postParseRow(&$r)
    {
        $r['idClient']  = $this->helperMapping->getMappingTitle('client_list', $r['idClient']);
        $r['status']    = $this->helperMapping->getMappingTitle('estimate_status', $r['status']);
        $r['issueDate'] = $this->helperFormatter->format($r['issueDate'], 'date');
        $r['dueDate']   = $this->helperFormatter->format($r['dueDate'], 'date');

        $discount     = $r['totalAmount'] * $r['discount'];
        $tax          = ($r['totalAmount'] - $discount) * $r['tax'];
        $r['amount']  = $this->helperFormatter->format(round($r['totalAmount'] - $discount + $tax, 2), 'money');
        $r['payment'] = $this->helperFormatter->format(round($r['totalPayment'], 2), 'money');
    }

}