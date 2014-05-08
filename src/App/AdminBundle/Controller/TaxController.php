<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Business\Tax\Utils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class TaxController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'admin'
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
            'title'          => 'Tax Groups',
            'base_route'     => 'app_admin_tax',
            'base_view'      => 'AppAdminBundle:Tax',
            'grid_title'     => 'Tax Groups',
            'grid_handler'   => 'app_admin.business.tax.grid_handler',
            'create_handler' => 'app_admin.business.tax.create_handler',
            'edit_handler'   => 'app_admin.business.tax.edit_handler',
            'delete_handler' => 'app_admin.business.tax.delete_handler'
        );
    }
    public function calculateAction(Request $request)
    {
        $client = $request->query->get('client');
        $tax = $request->query->get('tax');

        $result = Utils::calculateTaxByClient($this, $client, $tax);

        return new Response($result);
    }
}