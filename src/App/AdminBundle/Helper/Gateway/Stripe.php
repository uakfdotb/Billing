<?php
namespace App\AdminBundle\Helper\Gateway;

class Stripe
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Method to help generate a form to get the correct credentials for this gateway
     */
    public static function credentialsDataFormat()
    {
        return array(
            'gateway_type'            => '_stripe',
            'name'                    => 'Stripe',
            'auth_modes'              => array(
                'auth_mode' =>
                    array(
                        'auth_mode_type'      => 'partner',
                        'name'                => 'Partner',
                        'credentials'         => array(
                            'credential' => array(
                                array(
                                    'name'            => 'publishable_key',
                                    'label'           => 'Publishable key',
                                    'safe'            => true
                                ),
                                array(
                                    'name'            => 'secret_key',
                                    'label'           => 'Secret key',
                                    'safe'            => true
                                )
                            )
                        )
                    )
            ),
            'payment_methods'         => array(
                'stripe'
            ),
            'gateway_specific_fields' => array(

            ),
            'supported_countries'     => 'GB, US',
            'homepage'                => 'http://www.stripe.com/'
        );
    }

    /**
     * @param $credentials array of credentials as specified above to verify
     * @return array of field errors, or an empty array if there are none
     */
    public static function verifyCredentials($credentials)
    {
        $result = array();
        if(empty($credentials['publishable_key'])) $result[] = 'Invalid publishable key';
        if(empty($credentials['secret_key'])) $result[] = 'Invalid secret key';

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
    public static function getTemplateData($container, $tenant, $currency, $credentials, $invoice, $logo)
    {
        return array(
            'publishable'   => $credentials['publishable_key'],
            'total'         => $invoice->getTotalAmount(),
            'item_number'   => $invoice->getNumber(),
            'currency_code' => $currency,
            'logo'          => $logo,
            'invoice'       => $invoice,
            'gateway'       => ['name' => 'Stripe']
        );
    }

    /**
     * Method to deal with post data on the credit card form
     * @param $request Request object
     * @param $client Client the client who is paying
     * @param $currency string the 3-letter currency code
     * @param $credentials array of credentials specific to payment gateway and hopefully named correctly
     * @param $invoice Invoice the invoice object that we are paying
     * @return array
     */
    public static function postForm($request, $client, $currency, $credentials, $invoice)
    {
        try{
            \Stripe::setApiKey($credentials['secret_key']);

            $customer = \Stripe_Customer::create(array(
                'email' => $client->getEmail(),
                'card'  => $request->request->get('stripeToken')
            ));

            $charge = \Stripe_Charge::create(array(
                'customer' => $customer->id,
                'amount'   => $invoice->getTotalAmount() * 100,
                'currency' => $currency
            ));

            return $charge->id;
        } catch (\Stripe_CardError $e) {
            return false;
        }
    }

}