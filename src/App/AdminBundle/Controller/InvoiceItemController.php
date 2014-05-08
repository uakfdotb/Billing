<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;
use App\ClientBundle\Entity\ClientInvoice;
use App\ClientBundle\Entity\ClientPayment;
use App\ClientBundle\Entity\Config;
use App\AdminBundle\Business\Invoice\Constants;
use App\AdminBundle\Exception\MaximumClientsException;
use Symfony\Component\HttpFoundation\Request;
use App\AdminBundle\Business\Invoice\Utils;
use App\PaymentsBundle\Entity\PaymentGateway;
use GoCardless_Client;

class InvoiceItemController extends InlineCrudController
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

    public function postList(&$data, $action)
    {
        parent::postList($data, $action);

        $data['projectTypesListUrl']      = $this->generateUrl('app_admin_mapping_getKendoMapping', array('code' => 'project_type', 'withNull' => 1));
        $data['refundUrl']                = $this->generateUrl('app_admin_invoice_payment_refund');
        $data['invoiceViewClientSideUrl'] = $this->generateUrl('app_admin_invoice_view');
        $data['invoiceSendEmailUrl']      = $this->generateUrl('app_admin_invoice_send_email');

        /** @var ClientInvoice $invoice */
        $invoice         = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ClientInvoice');
        $data['invoice'] = $invoice;

        if (($invoice->getStatus() == ClientInvoice::STATUS_UNPAID)
            && ($invoice->getDueDate())
            && ($invoice->getTotalAmount())
            && ($invoice->getIssueDate())) {

            $client = Business\GlobalUtils::getClientById($this->container, $invoice->getIdClient());

            $subdomain = $this->container->getParameter('client_subdomain');
            $token = md5($invoice->getId() . $subdomain);
            $url = $this->generateUrl('app_client_invoice_show', ['id' => $invoice->getId(), 'token' => $token], true);

            $invoice->setInvoiceAccessToken($token);

            $em = $this->getDoctrine()->getManager();
            $em->persist($invoice);
            $em->flush();

            //$this->get('app_admin.mailer')->sendEmail($options);
            //$this->get('app_admin.mailer')->flushQueue();
        }

        // Add invoice edit form
        $handler = $this->get('app_admin.business.invoice.edit_handler');
        $handler->execute();
        $data['form'] = $this->get('app_admin.helper.form')->buildEditFormView($handler, array('route' => 'app_admin_invoice_item_list'));

        // Set grid to single page
        $data['singlePageGrid'] = true;

        // Payments
        $data['gridPayment'] = array(
            'jsId'      => 'GridPayment',
            'title'     => 'Payment',
            'config'    => array(
                'editable' => 'inline',
            ),
            'readUrl'   => $this->generateUrl('app_admin_invoice_payment_read', $this->get('request')->query->all()),
            'createUrl' => $this->generateUrl('app_admin_invoice_payment_create', $this->get('request')->query->all()),
            'editUrl'   => $this->generateUrl('app_admin_invoice_payment_edit'),
            'deleteUrl' => $this->generateUrl('app_admin_invoice_payment_delete', $this->get('request')->query->all())
        );

        // Breadscrum
        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_invoice_list'), 'title' => 'Invoice List'),
            array('href' => '#', 'title' => 'View invoice')
        );
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Invoice Item',
            'base_route'     => 'app_admin_invoice_item',
            'base_view'      => 'AppAdminBundle:InvoiceItem',
            'grid_title'     => 'View Invoice',
            'grid_handler'   => 'app_admin.business.invoice_item.grid_handler',
            'create_handler' => 'app_admin.business.invoice_item.create_handler',
            'edit_handler'   => 'app_admin.business.invoice_item.edit_handler',
            'delete_handler' => 'app_admin.business.invoice_item.delete_handler'
        );
    }
    /*public function paypalReturnAction()
    {
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        // post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

        // assign posted variables to local variables
        $item_number = isset($_POST['item_number']) ? $_POST['item_number'] : NULL;
        $payment_status = isset($_POST['payment_status']) ? $_POST['payment_status'] : NULL;
        $payment_amount = isset($_POST['mc_gross']) ? $_POST['mc_gross'] : NULL;
        $payment_currency = isset($_POST['mc_currency']) ? $_POST['mc_currency'] : NULL;
        $txn_id = isset($_POST['txn_id']) ? $_POST['txn_id'] : NULL;
        $receiver_email = isset($_POST['receiver_email']) ? $_POST['receiver_email'] : NULL;
        $payer_email = isset($_POST['payer_email']) ? $_POST['payer_email'] : NULL;
        $fee = isset($_POST['mc_fee']) ? $_POST['mc_fee'] : NULL;

        $em = $this->getDoctrine()->getManager();

        if (!$fp) {
            // HTTP ERROR
        } else {
            fputs ($fp, $header . $req);
            while (!feof($fp)) {
                $res = fgets ($fp, 1024);
                if (strcmp ($res, "VERIFIED") == 0) {
                    // check the payment_status is Completed
                    if(strcmp ($payment_status, "Completed") == 0){
                        // check that txn_id has not been previously processed
                        $transactions = $em->getRepository('AppClientBundle:ClientPayment')->findBy(['transaction' => $txn_id ]);
                        if(count($transactions) < 1)
                        {
                            // check that receiver_email is your Primary PayPal email
                            $receivers = $em->getRepository('AppPaymentsBundle:PaymentGateway')->findOneBy(['type' => '_paypal']); // This won't work for multiple Paypal accounts
                            $gatewayCredentials = $receivers->getSafeCredentials();
                            if($gatewayCredentials['email'] == $receiver_email)
                            {
                                $invoice = $em->getRepository('AppClientBundle:ClientInvoice')->findOneBy(['number' => $item_number, 'totalAmount' => $payment_amount]);
                                $currency = $this->get('app_admin.helper.common')->getConfig()->getBillingCurrency();
                                // check that payment_amount/payment_currency are correct
                                if($currency == $payment_currency && isset($invoice))
                                {
                                    // Mark invoice as paid
                                    $invoice->setTotalPayment($payment_amount);
                                    $em->persist($invoice);

                                    // Add payment to DB
                                    $payment = new ClientPayment();
                                    $payment->setIdGateway(1);
                                    $payment->setTransaction($txn_id);
                                    $payment->setPayDate(new \DateTime());
                                    $payment->setAmount($payment_amount);
                                    $payment->setIdInvoice($invoice->getId());
                                    $payment->setStatus(1); // Completed - since there is no delay with Paypal (Echeque??)
                                    $payment->setFee($fee);
                                    $em->persist($payment);

                                    $em->flush();

                                    // Update invoice status
                                    Utils::updateInvoiceStatus($this->container, $invoice->getId());

                                    // Increment invoice proforma paid count
                                    $config = $this->get('app_admin.helper.common')->getConfig();
                                    $config->incrementPaidProformaInvoice();
                                }
                            }
                        }
                    }
                }
            }
            fclose ($fp);
        }
        return new Response("ok");
    }
    public function goCardlessReturnAction(Request $request)
    {
        // Create a new GoCardless client object
        $account_details = array(
            'app_id'        => $this->get('service_container')->getParameter('gocardless_identifier'),
            'app_secret'    => $this->get('service_container')->getParameter('gocardless_secret')
        );

        $gocardless_client = new GoCardless_Client($account_details);

        // resource_uri and state are optional
        $confirm_params = array(
            'resource_id'    => $request->query->get('resource_id'),
            'resource_type'  => $request->query->get('resource_type'),
            'resource_uri'   => $request->query->get('resource_uri'),
            'signature'      => $request->query->get('signature')
        );

        // State is optional
        if ($request->query->get('state')) {
            $confirm_params['state'] = $request->query->get('state');
        }

        // Returns the confirmed resource if successful, otherwise throws an exception
        $confirm_result = $gocardless_client->confirm_resource($confirm_params);

        if($confirm_result){
            $em = $this->getDoctrine()->getManager();
            $invoice = $this->getDoctrine()->getRepository("AppClientBundle:ClientInvoice")->findOneById($request->query->get('state'));

            // Mark invoice as paid
            $invoice->setTotalPayment($invoice->getTotalAmount());
            $em->persist($invoice);

            // Add payment to DB
            $payment = new ClientPayment();
            $payment->setIdGateway(2);
            $payment->setTransaction($confirm_params['resource_id']);
            $payment->setPayDate(new \DateTime());
            $payment->setAmount($invoice->getTotalAmount());
            $payment->setIdInvoice($invoice->getId());
            $payment->setStatus(2); // Processing - since there is a delay with GoCardless
            $em->persist($payment);

            $em->flush();

            // Update invoice status
            Utils::updateInvoiceStatus($this->container, $invoice->getId());

            // Increment invoice proforma paid count
            $config = $this->get('app_admin.helper.common')->getConfig();
            $config->incrementPaidProformaInvoice();

            return new Response("ok");
        }

    }*/
    public function goCardlessAuthAction(Request $request)
    {
        // Create a new GoCardless client object
        $account_details = array(
            'app_id'        => $this->get('service_container')->getParameter('gocardless_identifier'),
            'app_secret'    => $this->get('service_container')->getParameter('gocardless_secret')
        );

        $gocardless_client = new GoCardless_Client($account_details);

        // If we have to redirect to GoCardless to get authorisation
        if(!$request->query->get('code'))
        {
            $authorize_url_options = array(
                'redirect_uri' => $request->getUri()
            );

            $authorize_url = $gocardless_client->authorize_url($authorize_url_options);
            return new Response($authorize_url);
        } else {
            // If we already have the code
            $params = array(
                'client_id'     => $this->get('service_container')->getParameter('gocardless_identifier'),
                'code'          => $request->query->get('code'),
                'redirect_uri'  => $this->get('router')->generate('app_admin_gocardless_callback', array(), true),
                'grant_type'    => 'authorization_code'
            );

            // Fetching token returns merchant_id and access_token
            $token = $gocardless_client->fetch_access_token($params);

            return new Response($token);
        }
    }
}
