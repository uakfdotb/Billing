<?php
namespace App\AdminBundle\Business\PhysicalItemPurchase;

use App\AdminBundle\Business\Base\BaseDeleteHandler;
use App\ClientBundle\Entity;

class DeleteHandler extends BaseDeleteHandler
{
    public function execute()
    {
        $model = $this->getModel();
        $model = $model[0];

        $this->helperDoctrine->deleteOneById('AppClientBundle:PhysicalItemPurchase', $model['id']);
    }
}
