<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class ServerController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'automation'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('automation/servers');
        $this->checkAutomationStatus();
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Server',
            'base_route'     => 'app_admin_server',
            'base_view'      => 'AppAdminBundle:Server',
            'grid_title'     => 'Server List',
            'grid_handler'   => 'app_admin.business.server.grid_handler',
            'create_handler' => 'app_admin.business.server.create_handler',
            'edit_handler'   => 'app_admin.business.server.edit_handler',
            'delete_handler' => 'app_admin.business.server.delete_handler'
        );
    }
}
