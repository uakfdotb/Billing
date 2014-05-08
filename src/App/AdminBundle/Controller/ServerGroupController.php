<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class ServerGroupController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'automation'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('automation/server_groups');
        $this->checkAutomationStatus();
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Server Group',
            'base_route'     => 'app_admin_server_group',
            'base_view'      => 'AppAdminBundle:ServerGroup',
            'grid_title'     => 'Server Group List',
            'grid_handler'   => 'app_admin.business.server_group.grid_handler',
            'create_handler' => 'app_admin.business.server_group.create_handler',
            'edit_handler'   => 'app_admin.business.server_group.edit_handler',
            'delete_handler' => 'app_admin.business.server_group.delete_handler'
        );
    }
}
