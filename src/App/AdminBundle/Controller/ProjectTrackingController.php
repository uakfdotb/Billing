<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class ProjectTrackingController extends InlineCrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'customer'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('customer/projects');
    }

    public function postList(&$data, $action)
    {
        parent::postList($data, $action);
        $helperMapping = $this->get('app_admin.helper.mapping');

        $project                        = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ClientProject');
        $data['project']                = $project;
        $data['projectTrackingBillUrl'] = $this->generateUrl('app_admin_project_tracking_bill');
        $data['projectTaskBillUrl']     = $this->generateUrl('app_admin_project_task_bill');
        // Add invoice edit form
        $handler = $this->get('app_admin.business.project.edit_handler');
        $handler->execute();
        $data['form'] = $this->get('app_admin.helper.form')->buildEditFormView($handler, array('route' => 'app_admin_project_tracking_list'));

        // Mapping
        $data['staffMemberListUrl']  = $this->generateUrl('app_admin_mapping_getKendoMapping', array('code' => 'staff_list', 'withNull' => 1));
        $data['taskUnitListUrl']     = $this->generateUrl('app_admin_mapping_getKendoMapping', array('code' => 'task_unit', 'withNull' => 1));
        $data['taskStatusListUrl']   = $this->generateUrl('app_admin_mapping_getKendoMapping', array('code' => 'task_status', 'withNull' => 1));
        $data['taskWorkTypeListUrl'] = $this->generateUrl('app_admin_mapping_getKendoMapping', array('code' => 'project_type', 'withNull' => 1));

        $data['invoiceList'] = array(0 => '(Create new invoice)') + $helperMapping->getMapping('invoice_list');


        // Attachments
        $data['gridAttachment'] = array(
            'jsId'      => 'GridAttachment',
            'title'     => 'Project attachment',
            'readUrl'   => $this->generateUrl('app_admin_project_attachment_read', $this->get('request')->query->all()),
            'createUrl' => $this->generateUrl('app_admin_project_attachment_create', $this->get('request')->query->all()),
            'editUrl'   => $this->generateUrl('app_admin_project_attachment_edit'),
            'deleteUrl' => $this->generateUrl('app_admin_project_attachment_delete', $this->get('request')->query->all()),
            'exportUrl' => $this->generateUrl('app_admin_project_attachment_export', $this->get('request')->query->all())
        );

        // Task
        $data['gridTask'] = array(
            'jsId'      => 'GridTask',
            'title'     => 'Project task',
            'readUrl'   => $this->generateUrl('app_admin_project_task_read', $this->get('request')->query->all()),
            'createUrl' => $this->generateUrl('app_admin_project_task_create', $this->get('request')->query->all()),
            'editUrl'   => $this->generateUrl('app_admin_project_task_edit'),
            'deleteUrl' => $this->generateUrl('app_admin_project_task_delete', $this->get('request')->query->all()),
            'exportUrl' => $this->generateUrl('app_admin_project_task_export', $this->get('request')->query->all()),
            'config'    => array(
                'editable' => 'inline'
            )
        );

        // Breadscrum
        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_project_list'), 'title' => 'Project List'),
            array('href' => '#', 'title' => 'View project')
        );
    }

    public function getConfiguration()
    {
        return array(
            'base_route'     => 'app_admin_project_tracking',
            'base_view'      => 'AppAdminBundle:ProjectTracking',
            'grid_title'     => 'View Project',
            'grid_handler'   => 'app_admin.business.project_tracking.grid_handler',
            'create_handler' => 'app_admin.business.project_tracking.create_handler',
            'edit_handler'   => 'app_admin.business.project_tracking.edit_handler',
            'delete_handler' => 'app_admin.business.project_tracking.delete_handler'
        );
    }

    public function billAction()
    {
        $idProject         = $this->getRequest()->query->get('id', 0);
        $trackingItems     = $this->getRequest()->query->get('projectTrackingItems', '');
        $arr               = explode(',', $trackingItems);
        $trackingItemArray = array();
        foreach ($arr as $v) {
            if (!empty($v)) {
                $trackingItemArray[] = intval($v);
            }
        }
        $resp = Business\ProjectTracking\Utils::bill($this, $idProject, $trackingItemArray);

        return new Response($resp ? "ok" : 'failed');
    }
}
