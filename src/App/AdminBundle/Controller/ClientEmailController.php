<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class ClientEmailController extends BaseController
{
    public function preProcess(&$data, $action)
    {
        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'admin'
            ));
        }
        //$this->get('app_admin.helper.billr_application')->checkPermission('admin/client_emails');

        $data['mappingUrl'] = $this->generateUrl('app_admin_mapping_getKendoMapping');
    }

    public function readAction()
    {
        $data = array();

        $gridHandler = $this->get('app_admin.business.client_email.grid_handler');
        $kendoGrid   = $this->get('app_admin.helper.kendo_grid');

        return $kendoGrid->handle($gridHandler);
    }

    public function exportAction()
    {
        $data = array();

        $gridHandler = $this->get('app_admin.business.client_email.grid_handler');
        $kendoGrid   = $this->get('app_admin.helper.kendo_grid');

        return $kendoGrid->handleExportCsv($gridHandler);
    }
}
