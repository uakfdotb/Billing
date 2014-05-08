<?php
namespace App\AdminBundle\Business\AccountTransaction;

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

        $timestamp = $this->helperCommon->formatKendoDatetime($model['timestamp']);

        $transaction = new Entity\AccountTransaction();
        $transaction->setIdAccount($this->container->get('request')->query->get('id', 0));
        $transaction->setIdDirection($model['idDirection']);
        $transaction->setTimestamp($timestamp);
        $transaction->setDescription($model['description']);
        $transaction->setAmount($model['amount']);

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        $this->newEntity = $transaction;

        parent::onSuccess();
    }
}
