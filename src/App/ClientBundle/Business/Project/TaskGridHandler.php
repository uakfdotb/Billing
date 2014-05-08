<?php
namespace App\ClientBundle\Business\Project;

use App\ClientBundle\Business\Base\BaseGridHandler;

class TaskGridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $idProject = $this->container->get('request')->query->get('id', 0);
        $query->select($baseObject)
            ->from('AppClientBundle:ClientProjectTask', $baseObject)
            ->andWhere($baseObject . '.idProject = :idProject')
            ->setParameter('idProject', $idProject);

        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }

    public function postParseRow(&$r)
    {
        $r['timestamp'] = $this->helperFormatter->format($r['timestamp'], 'datetime');
        $r['unitPrice'] = $this->helperFormatter->format($r['unitPrice'], 'money');
        $r['unit']      = $this->helperFormatter->format($r['unit'], 'mapping', 'task_unit');
        $r['status']    = $this->helperFormatter->format($r['status'], 'mapping', 'task_status');
    }

}