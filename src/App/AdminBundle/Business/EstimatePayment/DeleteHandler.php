<?php
namespace App\AdminBundle\Business\EstimatePayment;

use App\AdminBundle\Business\Base\BaseDeleteHandler;
use App\ClientBundle\Entity;

class DeleteHandler extends BaseDeleteHandler
{
    public function execute()
    {
        $model = $this->container->get('request')->query->get('models');
        $model = $model[0];

        $this->helperDoctrine->deleteOneById('AppClientBundle:ClientPayment', $model['id']);
    }
}
