<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class SupplierController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'supplier'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('supplier/suppliers');
    }

    public function postList(&$data, $action)
    {
        //$data['supplierPurchaseListUrl'] = $this->generateUrl('app_admin_supplier_purchase_list');
        //$data['grid']['editUrl'] = $this->generateUrl('app_admin_supplier_purchase_list');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Supplier',
            'base_route'     => 'app_admin_supplier',
            'base_view'      => 'AppAdminBundle:Supplier',
            'grid_title'     => 'Supplier List',
            'grid_handler'   => 'app_admin.business.supplier.grid_handler',
            'create_handler' => 'app_admin.business.supplier.create_handler',
            'edit_handler'   => 'app_admin.business.supplier.edit_handler',
            'delete_handler' => 'app_admin.business.supplier.delete_handler'
        );
    }
}
