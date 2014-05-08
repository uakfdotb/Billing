<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class EstimatePaymentController extends InlineCrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'customer'
            ));
        }
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Estimate Payment',
            'base_route'     => 'app_admin_estimate_payment',
            'base_view'      => 'AppAdminBundle:EstimatePayment',
            'grid_title'     => 'View Estimate Payment',
            'grid_handler'   => 'app_admin.business.estimate_payment.grid_handler',
            'create_handler' => 'app_admin.business.estimate_payment.create_handler',
            'edit_handler'   => 'app_admin.business.estimate_payment.edit_handler',
            'delete_handler' => 'app_admin.business.estimate_payment.delete_handler'
        );
    }

    public function refundAction()
    {
        $id = $this->get('request')->query->get('id', 0);
        if (Business\EstimatePayment\Utils::refund($this, $id)) {
            return new Response("ok");
        }

        return new Response("failed");
    }
}
