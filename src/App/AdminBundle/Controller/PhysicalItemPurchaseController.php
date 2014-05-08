<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class PhysicalItemPurchaseController extends InlineCrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit', 'export', 'bulk'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'supplier'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('supplier/inventory');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Item purchase',
            'base_route'     => 'app_admin_physical_item_purchase',
            'base_view'      => 'AppAdminBundle:PhysicalItemPurchase',
            'grid_title'     => 'Item purchase',
            'grid_handler'   => 'app_admin.business.physical_item_purchase.grid_handler',
            'create_handler' => 'app_admin.business.physical_item_purchase.create_handler',
            'edit_handler'   => 'app_admin.business.physical_item_purchase.edit_handler',
            'delete_handler' => 'app_admin.business.physical_item_purchase.delete_handler'
        );
    }

    public function bulkAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'bulk');

        $handler = $this->get('app_admin.business.physical_item_purchase.bulk_handler');
        $handler->execute();

        $id           = $this->getRequest()->get('id', 0);
        $physicalItem = $this->getDoctrine()->getRepository('AppClientBundle:PhysicalItem')->findOneById($id);

        $data['physicalItem'] = $physicalItem;
        $data['form']         = $this->get('app_admin.helper.form')->buildCreateFormView($handler, array('route' => 'app_admin_physical_item_purchase_bulk'));

        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_physical_item_list'), 'title' => 'Physical Item list'),
            array('href' => $this->generateUrl('app_admin_physical_item_detail', array('id' => $id)), 'title' => 'View physical item'),
            array('href' => '#', 'title' => 'Add physical item purchase')
        );
        return $this->render('AppAdminBundle:PhysicalItemPurchase:bulk.html.twig', $data);
    }
}
