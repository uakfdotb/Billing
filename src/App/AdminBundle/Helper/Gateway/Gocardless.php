<?php
namespace App\AdminBundle\Helper\Gateway;

use GoCardless_Client;

class Gocardless
{
    /**
     * Method to help generate a form to get the correct credentials for this gateway
     */
    public static function credentialsDataFormat()
    {
        return array(
            'gateway_type'            => '_gocardless',
            'name'                    => 'GoCardless',
            'auth_modes'              => array(
                'auth_mode' =>
                array(
                    'auth_mode_type'      => 'partner',
                    'name'                => 'Partner',
                    'credentials'         => array(
                        'credential' => array(
                            array(
                                'name'            => 'app_identifier',
                                'label'           => 'App identifier',
                                'safe'            => true
                            ),
                            array(
                                'name'            => 'access_token',
                                'label'           => 'Access token',
                                'safe'            => true
                            ),
                            array(
                                'name'            => 'app_secret',
                                'label'           => 'App secret',
                                'safe'            => true
                            )
                        )
                    )
                )
            ),
            'payment_methods'         => array(
                'gocardless'
            ),
            'gateway_specific_fields' => array(

            ),
            'supported_countries'     => 'GB',
            'homepage'                => 'http://www.gocardless.com/'
        );
    }


    /**
     * @param $credentials array of credentials as specified above to verify
     * @return array of field errors, or an empty array if there are none
     */
    public static function verifyCredentials($credentials)
    {
        $result = array();
        if(empty($credentials['app_identifier'])) $result[] = 'Invalid app identifier';
        if(empty($credentials['access_token'])) $result[] = 'Invalid access token';
        if(empty($credentials['app_secret'])) $result[] = 'Invalid app secret';

        return $result;
    }

    /**
     * Method to input Twig data into the fixed-location template file for this gateway.
     * This method is called by /src/App/InvoicePaymentsBundle/Controller/HomeController.php
     * @param $container
     * @param $tenant Tenant the tenant we're running
     * @param $currency string the 3-letter currency code
     * @param $credentials array of credentials specific to payment gateway and hopefully named correctly
     * @param $invoice Invoice the invoice object that we are paying
     * @param $logo the tenant's logo
     * @return array
     */

    public static function getTemplateData($container, $currency, $credentials, $invoice, $logo)
    {
        $account_details = array(
            'app_id'        => $container->getParameter('gocardless_identifier'),
            'app_secret'    => $container->getParameter('gocardless_secret')
        );

        $gocardless_client = new GoCardless_Client($account_details);

        $payment_details = array(
            'amount'  => $invoice->getTotalAmount(),
            'name'    => $invoice->getSubject(),
            'state'   => $invoice->getId()
        );

        $url = $gocardless_client->new_bill_url($payment_details);

        return $url;
    }

    public static function callback($container)
    {
        // Create a new GoCardless client object
        $account_details = array(
            'app_id'        => $container->getParameter('gocardless_identifier'),
            'app_secret'    => $container->getParameter('gocardless_secret')
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
            $em = $container->get('doctrine')->getManager();
            $invoice = $container->get('doctrine')->getRepository("AppClientBundle:ClientInvoice")->findOneById($request->query->get('state'));

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
            Utils::updateInvoiceStatus($container, $invoice->getId());

            // Increment invoice proforma paid count
            $config = $container->get('app_admin.helper.common')->getConfig();
            $config->incrementPaidProformaInvoice();
        }
    }
}