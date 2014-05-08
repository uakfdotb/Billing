<?php
namespace App\AdminBundle\Business\ProjectTracking;

use App\AdminBundle\Business\Base\BaseGridHandler;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $query->select($baseObject)
            ->from('AppClientBundle:ClientProjectTracking', $baseObject);
        $idProject = $this->container->get('request')->query->get('id', 0);
        $query->andWhere($baseObject . '.idProject = :idProject')
            ->setParameter('idProject', $idProject);

        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }

    public function postParseRow(&$r)
    {
        $r['start'] = $this->helperFormatter->format($r['start'], 'datetimekendo');
        $r['stop']  = $this->helperFormatter->format($r['stop'], 'datetimekendo');
    }

}