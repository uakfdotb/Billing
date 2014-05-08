<?php
namespace App\AdminBundle\Business\RecurringItem;

use App\AdminBundle\Business\Base\BaseInlineFormHandler;
use App\ClientBundle\Entity;

class CreateHandler extends BaseInlineFormHandler
{
    public $newId = 0;

    public function getDefaultValues()
    {
        $model = new CreateModel();

        return $model;
    }

    public function onSuccess()
    {
        $model = $this->container->get('request')->query->get('models');
        $model = $model[0];

        $item = new Entity\ClientRecurringItem();
        $item->setIdType($model['idType']);
        $item->setDescription($model['description']);
        $item->setQuantity($model['quantity']);
        $item->setUnitPrice($model['unitPrice']);
        $item->setIdRecurring($this->container->get('request')->query->get('id', 0));

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        $this->newEntity = $item;

        parent::onSuccess();
    }
}
