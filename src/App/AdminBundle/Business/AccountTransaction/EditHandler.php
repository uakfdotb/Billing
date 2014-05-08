<?php
namespace App\AdminBundle\Business\AccountTransaction;

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

        $timestamp = $this->helperCommon->formatKendoDatetime($model['timestamp']);

        $transaction = $this->helperDoctrine->findOneById('AppClientBundle:AccountTransaction', $model['id']);
        if ($timestamp) {
            $transaction->setTimestamp($timestamp);
        }
        $transaction->setIdDirection($model['idDirection']);
        $transaction->setDescription($model['description']);
        $transaction->setAmount($model['amount']);

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        $this->entity = $transaction;

        parent::onSuccess();
    }
}