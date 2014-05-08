<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Business\Tax\Utils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PermissionGroupController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'admin'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('admin/permission_group');
    }

    public function postList(&$data, $action)
    {
        //$data['supplierPurchaseListUrl'] = $this->generateUrl('app_admin_supplier_purchase_list');
        //$data['grid']['editUrl'] = $this->generateUrl('app_admin_supplier_purchase_list');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Permission Groups',
            'base_route'     => 'app_admin_permission_group',
            'base_view'      => 'AppAdminBundle:PermissionGroup',
            'grid_title'     => 'Permission Groups',
            'grid_handler'   => 'app_admin.business.permission_group.grid_handler',
            'create_handler' => 'app_admin.business.permission_group.create_handler',
            'edit_handler'   => 'app_admin.business.permission_group.edit_handler',
            'delete_handler' => 'app_admin.business.permission_group.delete_handler'
        );
    }
}