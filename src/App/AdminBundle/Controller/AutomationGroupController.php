<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class AutomationGroupController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'admin'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('automation/automation_groups');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Automation Group',
            'base_route'     => 'app_admin_automation_group',
            'base_view'      => 'AppAdminBundle:AutomationGroup',
            'grid_title'     => 'Automation Group List',
            'grid_handler'   => 'app_admin.business.automation_group.grid_handler',
            'create_handler' => 'app_admin.business.automation_group.create_handler',
            'edit_handler'   => 'app_admin.business.automation_group.edit_handler',
            'delete_handler' => 'app_admin.business.automation_group.delete_handler'
        );
    }
}
