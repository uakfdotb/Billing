<?php

namespace App\AdminBundle\Business\EstimatePayment;


use App\AdminBundle\Business\Base\BaseGridHandler;


class GridHandler extends BaseGridHandler
{

    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {

        $idEstimate = $this->container->get('request')->query->get('id', 0);

        $query->select($baseObject)

            ->from('AppClientBundle:ClientPayment', $baseObject)

            ->andWhere($baseObject . '.idEstimate = :idEstimate')

            ->setParameter('idEstimate', $idEstimate);


        // Build filter here - Consult arrayToSQL($filter, false)

        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);

    }


    public function postParseRow(&$r)
    {

        $r['payDate'] = $this->helperFormatter->format($r['payDate'], 'datetime');

        //$r['idGateway'] = $this->helperFormatter->format($r['idGateway'], 'mapping', 'gateway_list');

    }


}