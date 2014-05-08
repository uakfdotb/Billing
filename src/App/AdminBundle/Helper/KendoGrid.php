<?php
namespace App\AdminBundle\Helper;

use Symfony\Component\HttpFoundation\Response;

class KendoGrid
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function handle($gridHandler)
    {
        $query = $this->container->get('request')->query;

        // FILTER
        $array  = array();
        $filter = $query->get('filter', null);
        if ($filter !== null) {
            $gridHandler->setFilter($filter);
            // $refine = arrayToSQL($filter, false);
        }

        // SORT
        $sort = $query->get('sort', array(0 => array('field' => 'id', 'dir' => 'desc')));
        if (empty($sort[0]['field']) || empty($sort[0]['dir'])) {
            $sort[0] = array('field' => 'id', 'dir' => 'DESC');
        }
        $field = $sort[0]['field'];
        $dir   = $sort[0]['dir'];
        $gridHandler->setSort($field, $dir);

        // PAGER
        $take = $query->get('take', 10);
        $skip = $query->get('skip', 0);
        $gridHandler->setPager($skip, $take);

        // RETRIEVE DATA
        $resultArray = $gridHandler->getResultArray();
        $total       = $gridHandler->getTotalMatched();

        $callback = $query->get('callback', false);
        if ($callback !== false) {
            $response = new Response($callback . '(' . json_encode(array('data' => $resultArray, 'total' => $total)) . ')');
            $response->headers->set('Content-Type', 'application/x-javascript; charset=utf-8');
            return $response;
        }


        $response = new Response(json_encode(array('data' => $resultArray, 'total' => $total)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function handleExportCsv($gridHandler)
    {
        $query = $this->container->get('request')->query;

        // FILTER
        $array  = array();
        $filter = $query->get('filter', null);
        if ($filter !== null) {
            $gridHandler->setFilter($filter);
        }

        // SORT
        $sort = $query->get('sort', array(0 => array('field' => 'id', 'dir' => 'desc')));
        if (empty($sort[0]['field']) || empty($sort[0]['dir'])) {
            $sort[0] = array('field' => 'id', 'dir' => 'DESC');
        }
        $field = $sort[0]['field'];
        $dir   = $sort[0]['dir'];
        $gridHandler->setSort($field, $dir);

        // PAGER
        //$take = $query->get('take', 10);
        //$skip = $query->get('skip', 0);
        //$gridHandler->setPager($skip, $take);

        // RETRIEVE DATA
        $resultArray = $gridHandler->getResultArray();
        $csv         = '';
        $printHeader = false;
        foreach ($resultArray as $r) {
            if ($printHeader === false) {
                $header = array();
                foreach ($r as $k => $v) {
                    $header[] = $k;
                }
                $csv .= implode(',', $header) . "\n";
                $printHeader = true;
            }

            $row = array();
            foreach ($r as $value) {
                $row[] = ($value instanceof \DateTime) ? $value->format('d/m/Y') : $value;
            }
            $csv .= implode(',', $row) . "\n";
        }

        $response = new Response($csv);
        $response->headers->set('Content-Type', 'application/download');
        $response->headers->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Expires', '0');
        $response->headers->set('Content-Disposition', 'attachment;filename=csv_export.csv');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    private function buildFilterToken($baseObject, $field, $operator, $value)
    {
        $query = "${baseObject}.${field} ";
        switch ($operator) {
            case "eq":
                $query .= "= '${value}'";
                break;
            case "neq":
                $query .= "!= '${value}'";
                break;
            case "startswith":
                $query .= "LIKE '${value}%'";
                break;
            case "contains":
                $query .= "LIKE '%${value}%'";
                break;
            case "doesnotcontain":
                $query .= "NOT LIKE '%${value}%'";
                break;
            case "endswith":
                $query .= "LIKE '%${value}'";
                break;
            default:
                $query .= "= '${value}'";
        }
        return $query;
    }

    public function filter($dQuery, $baseObject, $filter)
    {
        if (isset($filter['filters'])) {
            $query = $this->getFilterQuery($baseObject, $filter);
            $dQuery->andWhere($query);
        }
    }

    private function getFilterQuery($baseObject, $filter)
    {
        if (isset($filter['filters']) && isset($filter['filters'][0])) {
            $query1 = $this->getFilterQuery($baseObject, $filter['filters'][0]);

            $queries   = array();
            $queries[] = '(' . $query1 . ')';
            $i         = 1;
            while (isset($filter['filters'][$i])) {
                $query2    = $this->getFilterQuery($baseObject, $filter['filters'][$i]);
                $queries[] = "(" . $query2 . ")";
                $i++;
            }
            return implode(' ' . $filter['logic'] . ' ', $queries);
        } else { // Token
            return $this->buildFilterToken($baseObject, $filter['field'], $filter['operator'], $filter['value']);
        }
    }
}
