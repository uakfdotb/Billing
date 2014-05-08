<?php
namespace Easy\CrudBundle\Grid;

abstract class BaseGridDataReader
{
    abstract function loadData($displayStart, $displayLength, $sortColumnNo, $sortDir);

    abstract function getTotalRecordsMatched();

    abstract function getResult();
}
