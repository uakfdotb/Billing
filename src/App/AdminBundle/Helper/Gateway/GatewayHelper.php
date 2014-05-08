<?php
namespace App\AdminBundle\Helper\Gateway;

class GatewayHelper
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
    public function sendPaymentReceived($options)
    {
        if($options['success'] === true){
            $subject = "Payment successfully received";
            $content = sprintf('The following payment has been received and logged successfully:<br /><br />Invoice: %s<br />Gateway: %s<br />Amount: %s<br />Transaction ID: %s',
                $options['invoice'],
                $options['gateway'],
                $options['amount'],
                $options['tid']);

            $mailTo = $this->container->get('app_admin.helper.common')->getConfig()->getPaymentSuccessEmail();
        }
        else {
            $subject = "Payment received with errors";
            $content = sprintf('The following payment was attempted but was not logged due to errors:<br /><br />Invoice: %s<br />Gateway: %s<br />Amount: %s<br />Transaction ID: %s<br />Errors: %s',
                $options['invoice'],
                $options['gateway'],
                $options['amount'],
                $options['tid'],
                $options['errors']);

            $mailTo = $this->container->get('app_admin.helper.common')->getConfig()->getPaymentFailureEmail();
        }

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom("no-reply@loadingdeck.com", "Loading Deck")
            ->setTo($mailTo)
            ->setBody($content, 'text/html');
            $this->container->get('mailer')->send($message);
    }
}