<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class ProjectTaskController extends InlineCrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'customer'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('customer/projects');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Project Task',
            'base_route'     => 'app_admin_project_task',
            'base_view'      => 'AppAdminBundle:ProjectTask',
            'grid_title'     => 'View Project',
            'grid_handler'   => 'app_admin.business.project_task.grid_handler',
            'create_handler' => 'app_admin.business.project_task.create_handler',
            'edit_handler'   => 'app_admin.business.project_task.edit_handler',
            'delete_handler' => 'app_admin.business.project_task.delete_handler'
        );
    }

    public function billAction()
    {
        $idProject     = $this->getRequest()->query->get('id', 0);
        $idInvoice     = $this->getRequest()->query->get('idInvoice', 0);
        $taskItems     = $this->getRequest()->query->get('projectTaskItems', '');
        $arr           = explode(',', $taskItems);
        $taskItemArray = array();
        foreach ($arr as $v) {
            if (!empty($v)) {
                $taskItemArray[] = intval($v);
            }
        }
        $resp = Business\ProjectTask\Utils::bill($this, $idProject, $taskItemArray, $idInvoice);

        return new Response($resp ? "ok" : 'failed');
    }
}
