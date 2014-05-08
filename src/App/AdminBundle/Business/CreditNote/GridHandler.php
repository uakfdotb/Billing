<?php
namespace App\AdminBundle\Business\CreditNote;

use App\AdminBundle\Business\Base\BaseGridHandler;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $query->select($baseObject)
            ->from('AppClientBundle:ClientCreditNote', $baseObject);

        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }

    public function postBuilderQuery($query)
    {
        $query->orderBy('p.id', 'DESC');
    }

    public function postParseRow(&$r)
    {
        $r['idClient'] = $this->helperFormatter->format($r['idClient'], 'mapping', 'client_list');
        $r['amount']   = $this->helperFormatter->format($r['amount'], 'money');
    }
}
