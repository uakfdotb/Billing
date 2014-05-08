<?php
namespace App\AdminBundle\Business\PhysicalItemPurchase;

use App\AdminBundle\Business\Base\BaseInlineFormHandler;
use App\ClientBundle\Entity;

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

        $dateIn = $this->helperCommon->formatKendoDatetime($model['dateIn']);

        $purchase = new Entity\PhysicalItemPurchase();
        $purchase->setIdPhysicalItem($this->container->get('request')->query->get('id', 0));
        $purchase->setIdSupplier($model['idSupplier']);
        $purchase->setDateIn($dateIn);
        $purchase->setPurchasePrice($model['purchasePrice']);
        $purchase->setQuantity($model['quantity']);
        $purchase->setSerialNumber($model['serialNumber']);

        $this->entityManager->persist($purchase);
        $this->entityManager->flush();

        $this->newEntity = $purchase;

        parent::onSuccess();
    }
}
