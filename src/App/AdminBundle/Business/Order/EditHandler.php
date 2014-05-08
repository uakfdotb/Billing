<?php
namespace App\AdminBundle\Business\Order;

use App\AdminBundle\Business\Base\BaseEditHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class EditHandler extends BaseEditHandler
{
    public function loadEntity()
    {
        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:ProductOrder');
    }
    public function getModelFromEntity($entity)
    {
        $model = new EditModel();
        $this->helperCommon->copyEntityToModel($this->entity, $model);

        return $model;
    }

    public function buildForm($builder)
    {
        return $builder;
    }

    public function onSuccess()
    {
        $model = $this->getForm()->getData();
        $entity = $this->getEntity();

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $entity);

        if ($entity->getStatus() == Constants::ORDER_STATUS_PAID) {
            Business\AutomationGroup\Utils::handlePostPaid($this->container, $entity);
        } else if ($entity->getStatus() == Constants::ORDER_STATUS_ACCEPTED) {
            Business\AutomationGroup\Utils::handlePostAccepted($this->container, $entity);
        }

        parent::onSuccess();
    }
}