<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class PhysicalItemSoldController extends InlineCrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit', 'export'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'supplier'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('supplier/inventory');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Item sold',
            'base_route'     => 'app_admin_physical_item_sold',
            'base_view'      => 'AppAdminBundle:PhysicalItemSold',
            'grid_title'     => 'Item sold',
            'grid_handler'   => 'app_admin.business.physical_item_sold.grid_handler',
            'create_handler' => 'app_admin.business.physical_item_sold.create_handler',
            'edit_handler'   => 'app_admin.business.physical_item_sold.edit_handler',
            'delete_handler' => 'app_admin.business.physical_item_sold.delete_handler'
        );
    }
}
