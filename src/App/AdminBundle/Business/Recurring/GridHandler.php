<?php
namespace App\AdminBundle\Business\Recurring;

use App\AdminBundle\Business\Base\BaseGridHandler;
use App\AdminBundle\Business;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $query->select($baseObject)
            ->from('AppClientBundle:ClientRecurring', $baseObject);

        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }

    public function postParseRow(&$r)
    {
        $r['idClient']   = $this->helperMapping->getMappingTitle('client_list', $r['idClient']);
        $r['idSchedule'] = $this->helperMapping->getMappingTitle('recurring_schedule', $r['idSchedule']);
        if ($r['nextDue'] != null) {
            $r['nextDue'] = $r['nextDue']->format('Y-m-d');
        }
    }


    function postGetResultArray(&$array)
    {
        $recurringIdArray = array(0);
        $recurringMap     = array();
        foreach ($array as $row) {
            $recurringIdArray[]       = $row['id'];
            $recurringMap[$row['id']] = $row;
        }

        // Compute amount
        $recurringAmount = array();
        $query           = $this->container->get('doctrine')->getEntityManager()->createQueryBuilder();
        $query->select('i')
            ->from('AppClientBundle:ClientRecurringItem', 'i')
            ->andWhere('i.idRecurring in (:recurringIdArray)')
            ->setParameter('recurringIdArray', $recurringIdArray);
        $result = $query->getQuery()->getResult();

        foreach ($result as $recurringItem) {
            // Get Tax
            $recurring = $this->container->get('doctrine')->getRepository('AppClientBundle:ClientRecurring')->findOneById($recurringItem->getIdRecurring());
            $client = Business\GlobalUtils::getClientById($this->container, $recurring->getIdClient());
            $taxValue = Business\Tax\Utils::calculateTaxByClient($this->container, $client, $recurring->getTax());

            $idRecurring = $recurringItem->getIdRecurring();
            if (!isset($recurringAmount[$idRecurring])) {
                $recurringAmount[$idRecurring] = 0;
            }
            if (isset($recurringMap[$idRecurring])) {
                $recurringAmount[$idRecurring] += $recurringItem->getQuantity() * $recurringItem->getUnitPrice() * (1 - $recurringMap[$idRecurring]['discount']) * (1 + $taxValue);
            }
        }

        // Money Rounding
        foreach ($recurringAmount as $idRecurring => $amount) {
            $recurringAmount[$idRecurring] = number_format($amount, 2);
        }

        // Set column data
        foreach ($array as $k => $v) {
            $idRecurring = $v['id'];

            // Amount
            $array[$k]['amount'] = 0.00;
            if (isset($recurringAmount[$idRecurring])) {
                $array[$k]['amount'] = $recurringAmount[$idRecurring];
            }
        }
    }
}