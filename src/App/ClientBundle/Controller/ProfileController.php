<?php



namespace App\ClientBundle\Controller;


use Symfony\Component\HttpFoundation\Response;

use App\AdminBundle\Business as AdminBusiness;


class ProfileController extends BaseController
{

    public function preProcess(&$data, $action)
    {

        if (in_array($action, array('list', 'create', 'edit'))) {

            $this->get('app_client.helper.billr_application_client')->buildViewHeader($data, array(

                'headerMenuSelected' => 'profile'

            ));

        }

        $this->get('app_client.helper.billr_application_client')->checkPermission(AdminBusiness\ClientContact\Constants::PAGE_PROFILE);
    }


    public function editAction()
    {

        $data = array();

        $this->executeFunction('preProcess', $data, 'edit');


        $handler = $this->get('app_client.business.profile.edit_handler');

        $handler->execute();


        $data['form'] = $this->get('app_admin.helper.form')->buildEditFormView($handler, array('route' => 'app_client_profile_edit'));


        return $this->render('AppClientBundle:Profile:edit.html.twig', $data);

    }

}