<?php
namespace App\AdminBundle\Business\EstimateItem;

use App\AdminBundle\Business\Base\BaseDeleteHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class DeleteHandler extends BaseDeleteHandler
{
    public function execute()
    {
        $model = $this->container->get('request')->query->get('models');
        $model = $model[0];

        $item       = $this->helperDoctrine->findOnebyId('AppClientBundle:ClientEstimateItem', $model['id']);
        $idEstimate = $item->getIdEstimate();

        $this->helperDoctrine->deleteOneById('AppClientBundle:ClientEstimateItem', $model['id']);

        Business\Estimate\Utils::updateEstimateStatus($this->container, $idEstimate);
    }
}
