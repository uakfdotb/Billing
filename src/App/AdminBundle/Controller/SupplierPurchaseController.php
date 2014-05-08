<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class SupplierPurchaseController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'supplier'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('supplier/supplier_purchases');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Supplier Purchase',
            'base_route'     => 'app_admin_supplier_purchase',
            'base_view'      => 'AppAdminBundle:SupplierPurchase',
            'grid_title'     => 'View Purchases',
            'grid_handler'   => 'app_admin.business.supplier_purchase.grid_handler',
            'create_handler' => 'app_admin.business.supplier_purchase.create_handler',
            'edit_handler'   => 'app_admin.business.supplier_purchase.edit_handler',
            'delete_handler' => 'app_admin.business.supplier_purchase.delete_handler'
        );
    }
    public function downloadAction()
    {
        $file = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:SupplierPurchaseFile')->getFile();
        $folder = $this->container->getParameter('supplier_upload_dir');
        $filename = $folder . '/' . $file;

        // Generate response
        $response = new Response();

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($filename));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '"');
        $response->headers->set('Content-length', filesize($filename));

        // Send headers before outputting anything
        $response->sendHeaders();

        $response->setContent(readfile($filename));

        return $response;
    }
}
