<?php

namespace App\ClientBundle\Controller;


use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business as AdminBusiness;


class ContactController extends BaseController
{

    public function preProcess(&$data, $action)
    {

        if (in_array($action, array('list', 'create', 'edit'))) {

            $this->get('app_client.helper.billr_application_client')->buildViewHeader($data, array(

                'headerMenuSelected' => 'contacts'

            ));

        }
        $this->get('app_client.helper.billr_application_client')->checkPermission(AdminBusiness\ClientContact\Constants::PAGE_CONTACT);
    }


    public function listAction()
    {
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_CONTACT_LIST);

        $data = array();

        $this->executeFunction('preProcess', $data, 'list');


        $gridHandler = $this->get('app_client.business.contact.grid_handler');

        $gridHandler->setPager(0, 100);

        $gridHandler->setSort('id', 'DESC');

        $data['grid'] = array(

            'data' => $gridHandler->getResultArray()

        );


        return $this->render('AppClientBundle:Contact:list.html.twig', $data);

    }


    public function createAction()
    {

        $data = array();

        $this->executeFunction('preProcess', $data, 'list');


        $handler = $this->get('app_client.business.contact.create_handler');

        $handler->execute();


        $data['form'] = $this->get('app_admin.helper.form')->buildCreateFormView($handler, array('route' => 'app_client_contact_create'));


        return $this->render('AppClientBundle:Contact:create.html.twig', $data);

    }


    public function editAction()
    {

        $data = array();

        $this->executeFunction('preProcess', $data, 'list');


        $handler = $this->get('app_client.business.contact.edit_handler');

        $handler->execute();


        $data['form'] = $this->get('app_admin.helper.form')->buildEditFormView($handler, array('route' => 'app_client_contact_edit'));


        return $this->render('AppClientBundle:Contact:edit.html.twig', $data);

    }


    public function deleteAction()
    {

        $handler = $this->get('app_client.business.contact.delete_handler');

        $handler->execute();


        return $this->redirect($this->generateUrl('app_client_contact_list'));

    }

}

