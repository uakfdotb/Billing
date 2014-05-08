<?php
namespace App\AdminBundle\Helper\Gateway;

use App\AdminBundle\Business\Invoice\Utils;
use App\ClientBundle\Entity\ClientPayment;

class Paypal
{
    /**
     * Method to help generate a form to get the correct credentials for this gateway
     */
    public static function credentialsDataFormat()
    {
        return array(
            'gateway_type'            => '_paypal',
            'name'                    => 'Paypal Standard',
            'auth_modes'              => array(
                'auth_mode' =>
                array(
                    'auth_mode_type'      => 'default',
                    'name'                => 'Default',
                    'credentials'         => array(
                        'credential' => array(
                            array(
                                'name'            => 'email',
                                'label'           => 'Paypal Email',
                                'safe'            => true
                            )
                        )
                    )
                )
            ),
            'payment_methods'         => array(
                'paypal'
            ),
            'gateway_specific_fields' => array(
            ),
            'supported_countries'     => 'ALL',
            'homepage'                => 'http://www.paypal.com/'
        );
    }

    /**
     * @param $credentials array of credentials as specified above to verify
     * @return array of field errors, or an empty array if there are none
     */
    public static function verifyCredentials($credentials)
    {
        $result = array();
        if(empty($credentials['email'])) $result[] = 'Invalid email';

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
            'business'      => $credentials['email'],
            'amount'        => $invoice->getTotalAmount(),
            'item_number'   => $invoice->getNumber(),
            'currency_code' => $currency,
            'notify_url'    => sprintf('https://%s.loadingdeck.com/return/paypal', $tenant->getSubdomain())
        );
    }


    /**
     * Method to invoke when https://[tenant].loadingdeck.com/return/[gateway] is called
     */
    public static function callback($container)
    {
        $em     = $container->get('doctrine');
        $errors = array();

        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';
        foreach ($container->get('request')->request->all() as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        // post back to PayPal system to validate
        $ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        if( !($res = curl_exec($ch)) ) {
            curl_close($ch);
            exit;
        }
        curl_close($ch);

        // assign posted variables to local variables
        $item_number = $container->get('request')->request->get('item_number', null);
        $payment_status = $container->get('request')->request->get('payment_status', null);
        $payment_amount = $container->get('request')->request->get('mc_gross', null);
        $payment_currency = $container->get('request')->request->get('mc_currency', null);
        $txn_id = $container->get('request')->request->get('txn_id', null);
        $receiver_email = $container->get('request')->request->get('receiver_email', null);
        $payer_email = $container->get('request')->request->get('payer_email', null);
        $fee = $container->get('request')->request->get('mc_fee', null);

        if (strcmp ($res, "VERIFIED") !== 0) {
            $errors[] = "The result was not verified by Paypal";
        }

        // check the payment_status is Completed
        if(strcmp ($payment_status, "Completed") !== 0){
            $errors[] = "The payment status was not \"complete\"";
        }

        // check that txn_id has not been previously processed
        $transactions = $em->getRepository('AppClientBundle:ClientPayment')->findBy(['transaction' => $txn_id ]);
        if(count($transactions) > 0)
        {
            $errors[] = "This transaction already exists in your database";
        }

        // check that receiver_email is your Primary PayPal email
        $receivers = $em->getRepository('AppPaymentsBundle:PaymentGateway')->findOneBy(['type' => '_paypal']); // This won't work for multiple Paypal accounts
        $gatewayCredentials = $receivers->getSafeCredentials();
        if($gatewayCredentials['email'] !== $receiver_email)
        {
            $errors[] = "The recipient Paypal address does not match the address set in your Loading Deck";
        }

        $invoice = $em->getRepository('AppClientBundle:ClientInvoice')->findOneBy(['number' => $item_number, 'totalAmount' => $payment_amount]);
        $currency = $container->get('app_admin.helper.common')->getConfig()->getBillingCurrency();
        // check that payment_amount/payment_currency are correct
        if($currency != $payment_currency || !isset($invoice))
        {
            $errors[] = "The payment currency or amount do not match the invoice";
        }

        if(count($errors) == 0)
        {
            // Mark invoice as paid
            $invoice->setTotalPayment($payment_amount);
            $em->getManager()->persist($invoice);

            // Add payment to DB
            $payment = new ClientPayment();
            $payment->setIdGateway(1);
            $payment->setTransaction($txn_id);
            $payment->setPayDate(new \DateTime());
            $payment->setAmount($payment_amount);
            $payment->setIdInvoice($invoice->getId());
            $payment->setStatus(1); // Completed - since there is no delay with Paypal (Echeque??)
            $payment->setFee($fee);
            $em->getManager()->persist($payment);
            $em->getManager()->flush();

            // Update invoice status
            Utils::updateInvoiceStatus($container, $invoice->getId());

            // Increment invoice proforma paid count
            $config = $container->get('app_admin.helper.common')->getConfig();
            $config->incrementPaidProformaInvoice();

            return [
                'success' => true,
                'amount'  => $payment_amount,
                'tid'     => $txn_id,
                'invoice' => empty($invoice) ? null : $invoice->getNumber()
            ];
        }

        return [
            'success' => false,
            'amount'  => $payment_amount,
            'tid'     => $txn_id,
            'errors'  => implode('<br />', $errors),
            'invoice' => empty($invoice) ? null : $invoice->getNumber()
        ];
    }
}