<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class ProductGroupController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'admin'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('automation/product_groups');
        $this->checkAutomationStatus();
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Product Group',
            'base_route'     => 'app_admin_product_group',
            'base_view'      => 'AppAdminBundle:ProductGroup',
            'grid_title'     => 'Product Group List',
            'grid_handler'   => 'app_admin.business.product_group.grid_handler',
            'create_handler' => 'app_admin.business.product_group.create_handler',
            'edit_handler'   => 'app_admin.business.product_group.edit_handler',
            'delete_handler' => 'app_admin.business.product_group.delete_handler'
        );
    }
}
