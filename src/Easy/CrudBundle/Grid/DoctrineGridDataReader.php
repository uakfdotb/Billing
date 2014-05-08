<?php
namespace Easy\CrudBundle\Grid;

abstract class DoctrineGridDataReader extends BaseGridDataReader
{
    protected $controller;
    protected $columnMapping = array();
    protected $result = NULL;
    protected $filter = NULL;

    public function getEntityManager()
    {
        return '';
    }

    public function setFilterData($filter)
    {
        $this->filter = $filter;
    }

    public function __construct($controller)
    {
        $this->controller    = $controller;
        $this->columnMapping = $this->getColumnMapping();
    }

    abstract function getColumnMapping();

    abstract function getFormatter();

    abstract public function buildQuery($queryBuilder);

    public function loadData($displayStart, $displayLength, $sortColumnNo, $sortDir)
    {
        $queryBuilder = $this->controller->getDoctrine()->getEntityManager($this->getEntityManager())->createQueryBuilder();
        $this->buildQuery($queryBuilder);
        $columnId = isset($this->columnMapping[$sortColumnNo]) ? $this->columnMapping[$sortColumnNo] : 'id';
        $queryBuilder->orderBy('p.' . $columnId, $sortDir);
        if ($displayLength != 0) {
            $queryBuilder->setFirstResult($displayStart);
            $queryBuilder->setMaxResults($displayLength);
        }

        $this->result = $queryBuilder->getQuery()->getResult();
    }

    public function getTotalRecordsMatched()
    {
        // FIX ME
        $queryBuilder = $this->controller->getDoctrine()->getEntityManager($this->getEntityManager())->createQueryBuilder();
        $this->buildQuery($queryBuilder);
        $queryBuilder = $queryBuilder->select('COUNT(p)');
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function getResult()
    {
        $data      = array();
        $formatter = $this->getFormatter();

        foreach ($this->result as $row) {
            $rowData = array();
            foreach ($this->columnMapping as $columnId) {
                if ($formatter->contains($columnId)) {
                    $rowData[] = $formatter->format($columnId, $row);
                } else {
                    $readMethod = 'buildCell' . ucfirst($columnId);
                    $getMethod  = 'get' . ucfirst($columnId);
                    if (method_exists($this, $readMethod)) {
                        $rowData[] = $this->$readMethod($row);
                    } else {
                        if (is_array($row) && isset($row[$columnId])) {
                            $rowData[] = $row[$columnId];
                        } else if (method_exists($row, $getMethod)) {
                            $rowData[] = $row->$getMethod();
                        } else {
                            $rowData[] = '';
                        }
                    }
                }
            }
            $rowId        = is_array($row) ? $row['id'] : $row->getId();
            $data[$rowId] = $rowData;
        }
        return $data;
    }
}
