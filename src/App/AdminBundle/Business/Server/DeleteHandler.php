<?php
namespace App\AdminBundle\Business\Server;

use App\AdminBundle\Business\Base\BaseDeleteHandler;
use App\ClientBundle\Entity;

class DeleteHandler extends BaseDeleteHandler
{
    public function execute()
    {
        $model = $this->getModel();

        // Delete the server
        $this->helperDoctrine->deleteOneById('AppClientBundle:Server', $model['id']);
    }
}
