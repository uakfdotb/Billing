<?php
namespace App\AdminBundle\Business\EstimateItem;

use App\AdminBundle\Business\Base\BaseInlineFormHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class CreateHandler extends BaseInlineFormHandler
{
    public $newEntity;

    public function getDefaultValues()
    {
        $model = new CreateModel();

        return $model;
    }

    public function onSuccess()
    {
        $model = $this->container->get('request')->query->get('models');
        $model = $model[0];

        $item = new Entity\ClientEstimateItem();
        $item->setIdType($model['idType']);
        $item->setDescription($model['description']);
        $item->setQuantity($model['quantity']);
        $item->setUnitPrice($model['unitPrice']);
        $item->setIdEstimate($this->container->get('request')->query->get('id', 0));

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        $this->newEntity = $item;

        Business\Estimate\Utils::updateEstimateStatus($this->container, $item->getIdEstimate());

        parent::onSuccess();
    }
}
