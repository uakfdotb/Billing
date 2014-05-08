<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class EmailController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit', 'mass_send'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'admin'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('admin/emails');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Email',
            'base_route'     => 'app_admin_email',
            'base_view'      => 'AppAdminBundle:Email',
            'grid_title'     => 'Email List',
            'grid_handler'   => 'app_admin.business.email.grid_handler',
            'create_handler' => 'app_admin.business.email.create_handler',
            'edit_handler'   => 'app_admin.business.email.edit_handler',
            'delete_handler' => 'app_admin.business.email.delete_handler'
        );
    }

    public function massSendAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'mass_send');

        $handler = $this->get('app_admin.business.email.mass_send_handler');
        $handler->execute();

        $data['form'] = $this->get('app_admin.helper.form')->buildCreateFormView($handler, array('route' => 'app_admin_email_mass_send'));

        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_email_list'), 'title' => 'Email list'),
            array('href' => '#', 'title' => 'Mass send')
        );

        return $this->render('AppAdminBundle:Email:mass_send.html.twig', $data);
    }

    public function postList(&$data, $action)
    {
        $data['grid']['createUrl'] = $this->generateUrl('app_admin_email_create');
    }
}
