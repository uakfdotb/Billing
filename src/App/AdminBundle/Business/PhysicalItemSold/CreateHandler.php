<?php
namespace App\AdminBundle\Business\PhysicalItemSold;

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

        $dateOut = $this->helperCommon->formatKendoDatetime($model['dateOut']);

        $sold = new Entity\PhysicalItemSold();
        $sold->setIdPhysicalItem($this->container->get('request')->query->get('id', 0));
        $sold->setIdClient($model['idClient']);
        $sold->setDateOut($dateOut);
        $sold->setSellPrice($model['sellPrice']);
        $sold->setQuantity($model['quantity']);
        $sold->setSerialNumber($model['serialNumber']);
        $sold->setInvoiced(0);


        $this->entityManager->persist($sold);
        $this->entityManager->flush();

        $this->newEntity = $sold;

        parent::onSuccess();
    }
}
