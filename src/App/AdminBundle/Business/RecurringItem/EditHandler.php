<?php
namespace App\AdminBundle\Business\RecurringItem;

use App\AdminBundle\Business\Base\BaseInlineFormHandler;
use App\ClientBundle\Entity;

class EditHandler extends BaseInlineFormHandler
{
    public $entity;

    public function getDefaultValues()
    {
        $model = new EditModel();

        return $model;
    }

    public function onSuccess()
    {
        $model = $this->container->get('request')->query->get('models');
        $model = $model[0];

        $item = $this->helperDoctrine->findOneById('AppClientBundle:ClientRecurringItem', $model['id']);
        $item->setIdType($model['idType']);
        $item->setDescription($model['description']);
        $item->setQuantity($model['quantity']);
        $item->setUnitPrice($model['unitPrice']);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        $this->entity = $item;

        parent::onSuccess();
    }
}