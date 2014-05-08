<?php

namespace App\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ClientController extends Controller
{
    public function createAction()
    {
        $data    = array();
        $handler = $this->get('app_client.business.client.create_handler');
        $handler->execute();
        $data['form'] = $this->get('app_admin.helper.form')->buildCreateFormView($handler, array('route' => 'app_client_client_create'));

        return $this->render('AppClientBundle:Client:create.html.twig', $data);
    }
}

