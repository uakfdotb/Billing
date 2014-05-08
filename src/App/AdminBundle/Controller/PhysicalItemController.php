<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class PhysicalItemController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit', 'export', 'detail'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'supplier'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('supplier/inventory');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Item',
            'base_route'     => 'app_admin_physical_item',
            'base_view'      => 'AppAdminBundle:PhysicalItem',
            'grid_title'     => 'Inventory',
            'grid_handler'   => 'app_admin.business.physical_item.grid_handler',
            'create_handler' => 'app_admin.business.physical_item.create_handler',
            'edit_handler'   => 'app_admin.business.physical_item.edit_handler',
            'delete_handler' => 'app_admin.business.physical_item.delete_handler'
        );
    }

    public function postList(&$data, $action)
    {
        $data['grid']['physicalItemDetailUrl'] = $this->generateUrl('app_admin_physical_item_detail');
    }

    public function readStockAction()
    {
        $id           = $this->get('request')->get('id', 0);
        $physicalItem = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:PhysicalItem');
        return new Response($physicalItem->getStock());
    }

    public function detailAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'detail');
        $id = $this->get('request')->get('id', 0);

        $data['soldItemInvoiceUrl'] = $this->generateUrl('app_admin_physical_item_makeInvoice');
        $data['bulkCreateUrl']      = $this->generateUrl('app_admin_physical_item_purchase_bulk', array('id' => $id));
        $data['addPurchaseUrl']     = $this->generateUrl('app_admin_physical_item_addPurchase');

        // Stock        
        $data['readStockUrl'] = $this->generateUrl('app_admin_physical_item_readStock', array('id' => $id));

        $helperMapping    = $this->get('app_admin.helper.mapping');
        $data['accounts'] = $helperMapping->getMapping('account_list');

        // Mapping
        $data['clientListUrl']   = $this->generateUrl('app_admin_mapping_getKendoMapping', array('code' => 'client_list', 'withNull' => 1));
        $data['supplierListUrl'] = $this->generateUrl('app_admin_mapping_getKendoMapping', array('code' => 'supplier_list', 'withNull' => 1));

        $physicalItem         = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:PhysicalItem');
        $data['physicalItem'] = $physicalItem;

        // Breadscrum
        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_physical_item_list'), 'title' => 'Inventory'),
            array('href' => '#', 'title' => 'View item')
        );

        // Sold
        $data['gridSold'] = array(
            'jsId'      => 'GridSold',
            'title'     => 'Sold',
            'readUrl'   => $this->generateUrl('app_admin_physical_item_sold_read', $this->get('request')->query->all()),
            'createUrl' => $this->generateUrl('app_admin_physical_item_sold_create', $this->get('request')->query->all()),
            'editUrl'   => $this->generateUrl('app_admin_physical_item_sold_edit'),
            'deleteUrl' => $this->generateUrl('app_admin_physical_item_sold_delete', $this->get('request')->query->all()),
            'exportUrl' => $this->generateUrl('app_admin_physical_item_sold_export', $this->get('request')->query->all()),
            'config'    => array(
                'editable' => 'inline'
            )
        );

        // Purchase
        $data['gridPurchase'] = array(
            'jsId'      => 'GridPurchase',
            'title'     => 'Purchase',
            'readUrl'   => $this->generateUrl('app_admin_physical_item_purchase_read', $this->get('request')->query->all()),
            'createUrl' => $this->generateUrl('app_admin_physical_item_purchase_create', $this->get('request')->query->all()),
            'editUrl'   => $this->generateUrl('app_admin_physical_item_purchase_edit'),
            'deleteUrl' => $this->generateUrl('app_admin_physical_item_purchase_delete', $this->get('request')->query->all()),
            'exportUrl' => $this->generateUrl('app_admin_physical_item_purchase_export', $this->get('request')->query->all()),
            'config'    => array(
                'editable' => 'inline'
            )
        );

        return $this->render('AppAdminBundle:PhysicalItem:detail.html.twig', $data);
    }

    public function makeInvoiceAction()
    {
        $idPhysicalItem = $this->getRequest()->query->get('id', 0);
        $soldItems      = $this->getRequest()->query->get('soldItems', '');
        $arr            = explode(',', $soldItems);
        $soldItemArray  = array();
        foreach ($arr as $v) {
            if (!empty($v)) {
                $soldItemArray[] = intval($v);
            }
        }
        $resp = Business\PhysicalItem\Utils::makeInvoice($this, $idPhysicalItem, $soldItemArray);

        return new Response($resp ? "ok" : 'failed');
    }

    public function addPurchaseAction()
    {
        $idPhysicalItem    = $this->getRequest()->query->get('id', 0);
        $purchaseItems     = $this->getRequest()->query->get('purchaseItems', '');
        $arr               = explode(',', $purchaseItems);
        $purchaseItemArray = array();
        foreach ($arr as $v) {
            if (!empty($v)) {
                $purchaseItemArray[] = intval($v);
            }
        }
        $resp = Business\PhysicalItem\Utils::addPurchase($this, $idPhysicalItem, $purchaseItemArray);

        return new Response($resp ? "ok" : 'failed');
    }
}
