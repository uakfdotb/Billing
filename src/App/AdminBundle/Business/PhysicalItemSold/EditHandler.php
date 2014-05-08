<?php
namespace App\AdminBundle\Business\PhysicalItemSold;

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

        $dateOut = $this->helperCommon->formatKendoDatetime($model['dateOut']);

        $sold = $this->helperDoctrine->findOneById('AppClientBundle:PhysicalItemSold', $model['id']);
        $sold->setIdClient($model['idClient']);
        if ($dateOut) {
            $sold->setDateOut($dateOut);
        }
        $sold->setSellPrice($model['sellPrice']);
        $sold->setQuantity($model['quantity']);
        $sold->setSerialNumber($model['serialNumber']);

        $this->entityManager->persist($sold);
        $this->entityManager->flush();

        $this->entity = $sold;

        parent::onSuccess();
    }
}