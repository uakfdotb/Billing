<?php

namespace App\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business as AdminBusiness;

class DefaultController extends BaseController
{
    public function indexAction()
    {
        $pages = array(
            AdminBusiness\ClientContact\Constants::PAGE_INVOICE => 'app_client_invoice_list',
            AdminBusiness\ClientContact\Constants::PAGE_TICKET  => 'app_client_ticket_list',
            AdminBusiness\ClientContact\Constants::PAGE_PROJECT => 'app_client_project_list',
            AdminBusiness\ClientContact\Constants::PAGE_ORDER   => 'app_client_order_list',
            AdminBusiness\ClientContact\Constants::PAGE_PROFILE => 'app_client_profile_edit',
            AdminBusiness\ClientContact\Constants::PAGE_CONTACT => 'app_client_contact_list',
        );

        return $this->redirect($this->generateUrl('app_client_dashboard_list'));
    }
}
