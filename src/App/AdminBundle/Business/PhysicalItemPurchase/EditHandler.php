<?php
namespace App\AdminBundle\Business\PhysicalItemPurchase;

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

        $dateIn = $this->helperCommon->formatKendoDatetime($model['dateIn']);

        $purchase = $this->helperDoctrine->findOneById('AppClientBundle:PhysicalItemPurchase', $model['id']);
        $purchase->setIdSupplier($model['idSupplier']);
        if ($dateIn) {
            $purchase->setDateIn($dateIn);
        }
        $purchase->setPurchasePrice($model['purchasePrice']);
        $purchase->setQuantity($model['quantity']);
        $purchase->setSerialNumber($model['serialNumber']);

        $this->entityManager->persist($purchase);
        $this->entityManager->flush();

        $this->entity = $purchase;

        parent::onSuccess();
    }
}