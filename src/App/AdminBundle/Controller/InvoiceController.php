<?php

namespace App\AdminBundle\Controller;

use App\ClientBundle\Entity\ClientInvoice;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;
use Knp\Snappy\Pdf;

class InvoiceController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'customer'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('customer/invoices');
    }

    public function postList(&$data, $action)
    {
        $data['invoiceItemListUrl']       = $this->generateUrl('app_admin_invoice_item_list');
        $data['invoiceViewClientSideUrl'] = $this->generateUrl('app_admin_invoice_view');
        $data['invoiceSendEmailUrl']      = $this->generateUrl('app_admin_invoice_send_email');
        $data['invoiceRefundUrl']         = $this->generateUrl('app_admin_invoice_refund');

        $data['grid']['editUrl'] = $this->generateUrl('app_admin_invoice_item_list');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Invoice',
            'base_route'     => 'app_admin_invoice',
            'base_view'      => 'AppAdminBundle:Invoice',
            'grid_title'     => 'Invoice List',
            'grid_handler'   => 'app_admin.business.invoice.grid_handler',
            'create_handler' => 'app_admin.business.invoice.create_handler',
            'edit_handler'   => 'app_admin.business.invoice.edit_handler',
            'delete_handler' => 'app_admin.business.invoice.delete_handler'
        );
    }

    public function sendEmailAction()
    {
        $invoice = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ClientInvoice');
        $msg     = $this->getRequest()->query->get('msg', '');
        if ($invoice != false && ($msg == 'invoice' || $msg == 'reminder' || $msg == 'overdue' || $msg == 'receipt')) {
            $res = Business\Invoice\Utils::sendInvoiceEmail($this, $invoice, $msg);
            if ($res === true) {
                return new Response('ok');
            } else {
                return new Response($res);
            }
        }
        return new Response('error');
    }

    public function createAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'create');
        $this->executeFunction('preCreate', $data, 'create');

        $handler = $this->get($this->configuration['create_handler']);
        $handler->execute();

        $data['form'] = $this->get('app_admin.helper.form')->buildCreateFormView($handler, array('route' => $this->configuration['base_route'] . '_create'));

        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl($this->configuration['base_route'] . '_list'), 'title' => $this->configuration['grid_title']),
            array('href' => '#', 'title' => 'Add ' . $this->configuration['title'])
        );

        $this->executeFunction('postCreate', $data, 'create');
        $this->executeFunction('postProcess', $data, 'create');

        if ($this->getRequest()->getMethod() == 'POST' && $handler->newId != 0) {
            $editUrl = $this->generateUrl('app_admin_invoice_item_list', array('id' => $handler->newId));
            return $this->redirect($editUrl);
        }

        return $this->render($this->configuration['base_view'] . ':create.html.twig', $data);
    }


    public function refundAction()
    {
        $id = $this->get('request')->query->get('id', 0);
        if (Business\Invoice\Utils::refund($this, $id)) {
            return new Response("ok");
        }
        return new Response("failed");
    }

    public function viewAction(Request $request)
    {
        $data = array();

        $em = $this->getDoctrine()->getManager();
        /** @var ClientInvoice $invoice */
        $invoice = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ClientInvoice');

        $mode    = $this->getRequest()->get('mode', '');

        $routes = array(
            'gateway' => 'app_admin_invoice_gateway',
            'pdf'     => 'app_admin_invoice_view'
        );

        Business\Invoice\Utils::prepareInvoicePage($this, $data, $invoice, $mode, $routes);

        $data['paymentsGateways'] = $this->getDoctrine()->getManager()->getRepository('AppPaymentsBundle:PaymentGateway')->findAll();
        list ($subdomain) = explode ('.', $request->getHttpHost());
        $data['subdomain'] = $subdomain;

        $resp = new Response();

        $logo = $data['config']->getLogo();
        $img = substr($logo, 0, strpos($logo, '?'));
        $data['config']->setLogo($img);
        $html   = $this->get('templating')->render('AppAdminBundle:Invoice:view_pdf.html.twig', $data);

        $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');

        $resp->headers->set('Content-Type', 'application/pdf; charset=UTF-8');
        $resp->headers->set('Content-Disposition', 'inline; filename=invoice.pdf');

        $resp->setContent($snappy->getOutputFromHtml($html));

        return $resp;
    }

    public function gatewayAction()
    {
        $gateway       = $this->get('request')->query->get('gateway', '');
        $gatewayConfig = $this->get('app_admin.helper.common')->getGatewayConfig();
        $config        = $this->get('app_admin.helper.common')->getConfig();
        $content       = '';

        $invoice = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ClientInvoice');
        switch ($gateway) {
            case 'paypal':
                if ($gatewayConfig->getPaypalEnabled()) {
                    $amount       = $this->get('request')->query->get('amount', '');
                    $subject      = $invoice->getSubject();
                    $business     = $gatewayConfig->getPaypalEmail();
                    $currencyCode = $config->getBillingCurrency();
                    $returnUrl    = $this->generateUrl('app_admin_invoice_view', array('id' => $invoice->getId()));
                    $ipnUrl       = $returnUrl; // Fix me

                    $invoiceId  = $invoice->getNumber();
                    $proceedImg = $this->getRequest()->getUriForPath('/../bundles/appadmin/images/proceed.gif');
                    $content    = "
                        <form name=\"_xclick\" action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" id=\"paypal\" style=\"padding: 0px; margin: 0px\">
                            <input type=\"hidden\" name=\"cmd\" value=\"_xclick\" />
                            <input type=\"hidden\" name=\"business\" value=\"$business\" />
                            <input type=\"hidden\" name=\"item_name\" value=\"$subject\" />
                            <input type=\"hidden\" name=\"currency_code\" value=\"$currencyCode\" />
                            <input type=\"hidden\" name=\"amount\" value=\"$amount\" />
                            <input type=\"hidden\" name=\"cancel_return\" value=\"$returnUrl\" />
                            <input type=\"hidden\" name=\"notify_url\" value=\"$ipnUrl\" />
                            <input type=\"hidden\" name=\"item_number\" value=\"$invoiceId\" />
                            <input type=\"image\" src=\"$proceedImg\" border=\"0\" name=\"submit\" style=\"padding-right: 10px\" />
                            </form>
                        <div class=\"clear\">";
                } else {
                    $content = 'Not enabled';
                }
                break;

            case 'bank':
                if ($gatewayConfig->getBankEnabled()) {
                    $notes   = nl2br($gatewayConfig->getBankTransferInstruction());
                    $content = "<div align='center' style='font-size: 12px;'>$notes</div>";
                } else {
                    $content = 'Not enabled';
                }
                break;
            default:
        }
        return new Response($content);
    }
}
