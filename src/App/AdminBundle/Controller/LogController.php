<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class LogController extends BaseController
{
    public function preProcess(&$data, $action)
    {
        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'admin'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('admin/logs');

        $data['mappingUrl'] = $this->generateUrl('app_admin_mapping_getKendoMapping');
    }

    public function listAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'list');

        $data['grid'] = array(
            'title'     => "Log",
            'readUrl'   => $this->generateUrl('app_admin_log_read', $this->get('request')->query->all()),
            'editUrl'   => '#',
            'createUrl' => '#',
            'deleteUrl' => '#',
            'exportUrl' => $this->generateUrl('app_admin_log_export', $this->get('request')->query->all()),
        );

        // Breadscrum
        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => '#', 'title' => 'Log')
        );

        return $this->render('AppAdminBundle:Log:list.html.twig', $data);
    }

    public function readAction()
    {
        $data = array();

        $gridHandler = $this->get('app_admin.business.log.grid_handler');
        $kendoGrid   = $this->get('app_admin.helper.kendo_grid');

        return $kendoGrid->handle($gridHandler);
    }

    public function exportAction()
    {
        $data = array();

        $gridHandler = $this->get('app_admin.business.log.grid_handler');
        $kendoGrid   = $this->get('app_admin.helper.kendo_grid');

        return $kendoGrid->handleExportCsv($gridHandler);
    }
}
