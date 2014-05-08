<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\AdminBundle\Business;

class ReportController extends BaseController
{
    public function listAction(Request $request)
    {
        $this->get('app_admin.helper.billr_application')->checkPermission('admin/report');

        $data = array();
        $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
            'headerMenuSelected' => 'admin'
        ));

        switch($request->query->get('report'))
        {
            case "accountant":
                $response = Business\Report\Utils::accountantReport($this);
                $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
                $response->headers->set('Content-Disposition', 'attachment;filename=full_report.xls');
                return $response;
            default:
                break;
        }
        return $this->render('AppAdminBundle:Report:list.html.twig', $data);
    }
}
