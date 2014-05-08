<?php
namespace App\AdminBundle\Business\ProjectTask;

use App\AdminBundle\Business\Base\BaseGridHandler;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $query->select($baseObject)
            ->from('AppClientBundle:ClientProjectTask', $baseObject);
        $idProject = $this->container->get('request')->query->get('id', 0);
        $query->andWhere($baseObject . '.idProject = :idProject')
            ->setParameter('idProject', $idProject);

        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }

    public function postParseRow(&$r)
    {
        $r['timestamp'] = $this->helperFormatter->format($r['timestamp'], 'datetime');
    }
}