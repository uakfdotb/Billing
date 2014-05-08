<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class ClientNoteController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'customer'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('customer/clients');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Client Note',
            'base_route'     => 'app_admin_client_note',
            'base_view'      => 'AppAdminBundle:ClientNote',
            'grid_title'     => 'Client Note List',
            'grid_handler'   => 'app_admin.business.client_note.grid_handler',
            'create_handler' => 'app_admin.business.client_note.create_handler',
            'edit_handler'   => 'app_admin.business.client_note.edit_handler',
            'delete_handler' => 'app_admin.business.client_note.delete_handler'
        );
    }

    public function postCreate(&$data, $action)
    {
        $idClient           = $this->getRequest()->get('id', 0);
        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_client_list'), 'title' => 'Client List'),
            array('href' => $this->generateUrl('app_admin_client_edit', array('id' => $idClient)), 'title' => 'Edit client'),
            array('href' => '#', 'title' => 'Add note')
        );
    }

    public function postEdit(&$data, $action)
    {
        $idNote   = $this->getRequest()->get('id', 0);
        $contact  = $this->getDoctrine()->getRepository('AppClientBundle:ClientNote')->findOneById($idNote);
        $idClient = $contact->getIdClient();

        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_client_list'), 'title' => 'Client List'),
            array('href' => $this->generateUrl('app_admin_client_edit', array('id' => $idClient)), 'title' => 'Edit client'),
            array('href' => '#', 'title' => 'Edit note')
        );
    }
}
