<?php

namespace App\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends BaseController
{
    public function preProcess(&$data, $action)
    {
        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_client.helper.billr_application_client')->buildViewHeader($data, array(
                'headerMenuSelected' => 'dashboard'
            ));
        }
    }

    public function listAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'list');

        return $this->render('AppClientBundle:Dashboard:list.html.twig', $data);
    }
}
