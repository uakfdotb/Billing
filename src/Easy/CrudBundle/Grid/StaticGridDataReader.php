<?php
namespace Easy\CrudBundle\Grid;

abstract class StaticGridDataReader extends BaseGridDataReader
{
    protected $data = array();

    abstract function setData($displayStart, $displayLength, $sortColumnNo, $sortDir);

    public function loadData($displayStart, $displayLength, $sortColumnNo, $sortDir)
    {
        $this->setData($displayStart, $displayLength, $sortColumnNo, $sortDir);
    }

    public function getTotalRecordsMatched()
    {
        return count($this->data);
    }

    function getResult()
    {
        return $this->data;
    }
}
