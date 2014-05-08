<?php

namespace App\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business as AdminBusiness;
use App\ClientBundle\Form\InvoicePaymentType;
use App\PaymentsBundle\Entity\PaymentGateway;
use App\ClientBundle\Entity\ClientPayment;
use App\AdminBundle\Business\Invoice\Constants;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider;
use App\ClientBundle\Entity\ClientInvoice;
use App\AdminBundle\Business\Invoice\Utils;

class InvoiceController extends BaseController
{
    private $credentials;

    public function preProcess(&$data, $action)
    {
        if (in_array($action, array('list', 'create', 'edit', 'view_client_site'))) {
            $this->get('app_client.helper.billr_application_client')->buildViewHeader($data, array(
                'headerMenuSelected' => 'invoices'
            ));
        }
        $this->get('app_client.helper.billr_application_client')->checkPermission(AdminBusiness\ClientContact\Constants::PAGE_INVOICE);
    }

    public function listAction()
    {
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_INVOICE_LIST);

        $data = array();
        $this->executeFunction('preProcess', $data, 'list');

        $gridHandler = $this->get('app_client.business.invoice.grid_handler');
        $gridHandler->setPager(0, 100);
        $gridHandler->setSort('id', 'DESC');
        $data['grid'] = array(
            'data' => $gridHandler->getResultArray()
        );

        $data['invoiceView']  = $this->generateUrl('app_client_invoice_view');

        return $this->render('AppClientBundle:Invoice:list.html.twig', $data);
    }

    public function showAction(Request $request, $id, $token)
    {
        $em      = $this->getDoctrine()->getManager();
        $data    = array();
        $invoice = $em->getRepository('AppClientBundle:ClientInvoice')->find($id);
        $mode    = $this->getRequest()->get('mode', '');

        // Get menu
        $this->executeFunction('preProcess', $data, 'list');

        // Mark invoice as viewed
        $invoice->setViewedByClient(1);
        $em->persist($invoice);
        $em->flush();

        // Get logo
        $config = $this->get('app_admin.helper.common')->getConfig();
        $config->getLogo() ?
            $data['logo'] = $this->container->getParameter('logo_url') . '/' .$config->getLogo() :
            $data['logo'] = NULL;

        $routes = array(
            'gateway' => 'app_admin_invoice_gateway',
            'pdf'     => 'app_admin_invoice_view'
        );

        \App\AdminBundle\Business\Invoice\Utils::prepareInvoicePage($this, $data, $invoice, $mode, $routes);

        $data['paymentsGateways'] = $em->getRepository('AppPaymentsBundle:PaymentGateway')->findAll();
        list ($subdomain) = explode ('.', $request->getHttpHost());
        $data['subdomain'] = $subdomain;

        // If we are redirecting to the payment page
        if($request->getMethod() == 'POST')
        {
            return $this->redirect(
                $this->generateUrl('app_client_invoice_pay', array(
                    'gateway' => $request->request->get('method'),
                    'id'      => $invoice->getId(),
                    'token'   => $invoice->getInvoiceAccessToken()
                ))
            );
        }

        // If we are showing the invoice
        if ($token == $invoice->getInvoiceAccessToken()) {
            return $this->render('AppClientBundle:Invoice:show.html.twig', $data);
        } else {
            throw new NotFoundHttpException(404, 'The invoice was not found');
        }
    }

    public function payAction(Request $request, $gateway, $id, $token)
    {
        $currency = $this->get('app_admin.helper.common')->getConfig()->getBillingCurrency();

        $em = $this->getDoctrine()->getManager();
        /** @var PaymentGateway $gateway */
        $gateway = $em->getRepository('AppPaymentsBundle:PaymentGateway')->findOneBy(['id' => $gateway]);
        if ($gateway === false) {
            throw $this->createNotFoundException('Unknown gateway.');
        }

        /** @var ClientInvoice $id */
        $id = $em->getRepository('AppClientBundle:ClientInvoice')->findOneBy(['id' => $id, 'invoiceAccessToken' => $token]);
        if (!isset($id)) {
            throw $this->createNotFoundException('Unknown invoice.');
        }

        if ($id->getStatus() == ClientInvoice::STATUS_PAID) {
            throw $this->createNotFoundException('This invoice has already been paid.');
        }

        // Get logo
        $config = $this->get('app_admin.helper.common')->getConfig();
        $config->getLogo() ?
            $logo = 'data:image/jpg;base64,' . base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/../'.$config->getLogo())) :
            $logo = NULL;

        // Non-CC gateways go here
        if(substr($gateway->getType(), 0, 1) == '_')
        {
            $class    = sprintf('\\App\\AdminBundle\\Helper\\Gateway\\%s', ucfirst(str_replace('_', '', $gateway->getType())));
            $template = sprintf('AppClientBundle:Payments:%s.html.twig', ucfirst(str_replace('_', '', $gateway->getType())));
            if($request->getMethod() == 'POST')
            {
                $data = call_user_func(
                    array($class, 'postForm'),
                    $request,
                    $this->getDoctrine()->getRepository('AppUserBundle:User')->findOneById($id->getIdClient()),
                    $currency,
                    $gateway->getSafeCredentials(),
                    $id
                );

                if($data !== false)
                {
                    // Mark invoice as paid
                    $id->setTotalPayment($id->getTotalAmount());
                    $em->persist($id);

                    // Add payment to DB
                    $payment = new ClientPayment();
                    $payment->setIdGateway(3); // Credit card
                    $payment->setTransaction($data);
                    $payment->setPayDate(new \DateTime());
                    $payment->setAmount($id->getTotalAmount());
                    $payment->setIdInvoice($id->getId());
                    $payment->setStatus(1); // Completed - since there is no delay (Echeque??)
                    $payment->setFee(0); // 0 for Stripe for now
                    $em->persist($payment);

                    $em->flush();

                    // Update invoice status
                    Utils::updateInvoiceStatus($this->container, $id->getId());

                    // Increment invoice proforma paid count
                    $config = $this->get('app_admin.helper.common')->getConfig();
                    $config->incrementCountProformaPaidInvoice();

                    // Send email
                    $this->get('app_admin.helper.gateway_helper')->sendPaymentReceived([
                        'success' => true,
                        'invoice' => $id->getId(),
                        'gateway' => 'stripe',
                        'amount'  => $id->getTotalAmount(),
                        'tid'     => $data
                    ]);

                    return new RedirectResponse($this->generateUrl('app_client_invoice_list'));
                }
                else
                {
                    $this->get('app_admin.helper.gateway_helper')->sendPaymentReceived([
                        'success' => false,
                        'invoice' => $id->getId(),
                        'gateway' => 'stripe',
                        'amount'  => $id->getTotalAmount(),
                        'tid'     => $data,
                        'errors'  => "There was an error with the card"
                    ]);
                }
            }
            $data = call_user_func(
                array($class, 'getTemplateData'),
                $this->container,
                $this->get('app_admin.helper.common')->getCurrentTenant(), // TO DO: Remove this and all references to this
                $currency,
                $gateway->getSafeCredentials(),
                $id,
                $logo
            );

            $this->get('app_client.helper.billr_application_client')->buildViewHeader($data);

            return $this->render($template, $data);
        }

        $form = $this->createForm(new InvoicePaymentType());
        $errors = array();

        if ($request->query->has('token')) {
            /*$csrfToken = $form->get('_token')->getData();
            // verificam daca csrf valid, daca nu e valid bagam in error['failed'] de fail
            if (!$csrfToken) {
                $errors['failed'] = 'Your payment has failed.';
                return $errors['failed'];
            }*/

            $pp = $this->processPayments($request->query->get('token'), $gateway, $id, $currency);

            if ($pp->succeeded == 'false' || $pp->succeeded == false) {
                $id->setTotalPayment($pp->amount / 100);
                $id->setStatus(Constants::ORDER_STATUS_FAILED);

                $errors['failed'] = 'Your payment has failed.';
            } else {
                $id->setTotalPayment($pp->amount / 100);
                $id->setStatus(ClientInvoice::STATUS_PAID);

                $errors['success'] = 'Thank you for shopping with us.';
            }

            $em->persist($id);
            $em->flush();
        }

        $intention = 'pay';
        $csrf = new SessionCsrfProvider($request->getSession(), $this->container->getParameter('secret')); //Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider by default
        $token = $csrf->generateCsrfToken($intention); //Intention should be empty string, if you did not define it in parameters

        return $this->render('AppInvoicePaymentsBundle:Home:pay.html.twig', array(
            'logo'            => $logo,
            'errors'          => $errors,
            'csrf_token'      => $token,
            'form'            => $form->createView(),
            'tenant'          => $tenant,
            'gateway'         => $gateway,
            'invoice'         => $id,
            'total'           => $this->get('app_admin.helper.formatter')->format($id->getTotalAmount(), 'money'),
            'environment_key' => $this->container->getParameter('payment.spreedly_environment_key'),
            'redirect_url'    => sprintf(
                '%s://%s/invoice/payments/%s/%s/%s/%s',
                $request->getScheme(),
                $request->getHttpHost(),
                $tenant->getSubdomain(),
                $gateway->getId(),
                $id->getId(),
                $id->getInvoiceAccessToken()
            )
        ));
    }

    private function processPayments($token, $gateway, $invoice, $currency)
    {
        $this->credentials = implode(':', array(
            $this->container->getParameter('payment.spreedly_environment_key'),
            $this->container->getParameter('payment.spreedly_access_secret'),
        ));

        $xml = '<transaction>
        <amount>%s</amount>
        <currency_code>%s</currency_code>
        <payment_method_token>%s</payment_method_token>
      </transaction>';

        $ch = curl_init(sprintf('https://core.spreedly.com/v1/gateways/%s/purchase.xml', $gateway->getToken()));
        curl_setopt($ch, CURLOPT_USERPWD,        $this->credentials);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST,           1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,     sprintf(
            $xml,
            intval($invoice->getTotalAmount() * 100),
            $currency,
            $token
        ));
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array(
            'Content-Type: application/xml'
        ));

        $result = new \SimpleXMLElement(curl_exec($ch));
        return $result;
    }
}

