<?php

namespace App\ClientBundle\Business\Contact;


use App\AdminBundle\Business\Base\BaseDeleteHandler;

use App\ClientBundle\Entity;
use App\AdminBundle\Business as AdminBusiness;


class DeleteHandler extends BaseDeleteHandler
{

    public function execute()
    {
        // Log
        $contact = $this->helperDoctrine->findOneByRequestId('AppClientBundle:ClientContact');
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_CONTACT_DELETE, 'Contact email: ' . $contact->getEmail());

        $this->helperDoctrine->deleteOneByRequestId('AppClientBundle:ClientContact');

    }

}

