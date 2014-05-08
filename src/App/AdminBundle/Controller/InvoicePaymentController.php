<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class InvoicePaymentController extends InlineCrudController
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
            'title'          => 'Invoice Payment',
            'base_route'     => 'app_admin_invoice_payment',
            'base_view'      => 'AppAdminBundle:InvoicePayment',
            'grid_title'     => 'View Invoice Payment',
            'grid_handler'   => 'app_admin.business.invoice_payment.grid_handler',
            'create_handler' => 'app_admin.business.invoice_payment.create_handler',
            'edit_handler'   => 'app_admin.business.invoice_payment.edit_handler',
            'delete_handler' => 'app_admin.business.invoice_payment.delete_handler'
        );
    }

    public function refundAction()
    {
        $id = $this->get('request')->query->get('id', 0);
        if (Business\InvoicePayment\Utils::refund($this, $id)) {
            return new Response("ok");
        }

        return new Response("failed");
    }
}
