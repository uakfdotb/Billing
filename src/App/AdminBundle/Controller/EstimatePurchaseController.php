<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class EstimatePurchaseController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'supplier'
            ));
        }
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Estimate Purchase',
            'base_route'     => 'app_admin_estimate_purchase',
            'base_view'      => 'AppAdminBundle:EstimatePurchase',
            'grid_title'     => 'Estimate purchase list',
            'grid_handler'   => 'app_admin.business.estimate_purchase.grid_handler',
            'create_handler' => 'app_admin.business.estimate_purchase.create_handler',
            'edit_handler'   => 'app_admin.business.estimate_purchase.edit_handler',
            'delete_handler' => 'app_admin.business.estimate_purchase.delete_handler'
        );
    }
    public function downloadAction()
    {
        $file = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ClientEstimatePurchaseFile')->getFile();
        $folder = $this->container->getParameter('estimate_purchase_upload_dir');
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
