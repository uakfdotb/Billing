<?php

namespace App\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class ReturnController extends Controller
{
    public function returnAction($gateway)
    {
        $class = sprintf("\\App\\AdminBundle\\Helper\\Gateway\\%s", ucfirst($gateway));
        $result = call_user_func(array($class, "callback"), $this->container);

        // Send email to tenant owner
        if($result['success'] === true)
        {
            $options['success'] = true;
            $options['gateway'] = $gateway;
            $options['amount']  = $result['amount'] ?: null;
            $options['tid']     = $result['tid'] ?: null;
            $options['invoice'] = $result['invoice'] ?: null;
        }
        else
        {
            $options['success'] = false;
            $options['gateway'] = $gateway;
            $options['amount']  = $result['amount'] ?: null;
            $options['tid']     = $result['tid'] ?: null;
            $options['errors']  = $result['errors'] ?: null;
            $options['invoice'] = $result['invoice'] ?: null;
        }
        $this->get('app_admin.helper.gateway_helper')->sendPaymentReceived($options);
        return new Response();
    }

}
