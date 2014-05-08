<?php
namespace Easy\CrudBundle\Grid;

// use http://datatables.net/
class DataTableAjaxSourceHandler
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function handle($gridDataReader)
    {
        $request = $this->container->get('request');

        $iDisplayStart  = $request->query->get('iDisplayStart', 0);
        $iDisplayLength = $request->query->get('iDisplayLength', 20);
        $iSortCol_0     = $request->query->get('iSortCol_0', 1);
        $sSortDir_0     = $request->query->get('sSortDir_0', 'asc');

        $gridDataReader->loadData($iDisplayStart, $iDisplayLength, $iSortCol_0 - 1, $sSortDir_0);
        $totalMatched = $gridDataReader->getTotalRecordsMatched();

        $d      = array();
        $result = $gridDataReader->getResult();
        foreach ($result as $id => $row) {
            $r1  = array(
                '<span class="checkbox"><input type="checkbox" id="row_' . $id . '" class="stdtableCheckBox" /></span>',
            );
            $d[] = array_merge($r1, $row);
        }

        $data                         = array();
        $data['sEcho']                = intval($_GET['sEcho']);
        $data['aaData']               = $d;
        $data['iTotalRecords']        = count($d);
        $data['iTotalDisplayRecords'] = $totalMatched;

        return $data;
    }
}

