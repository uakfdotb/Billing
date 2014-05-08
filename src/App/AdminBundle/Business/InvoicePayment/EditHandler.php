<?php
namespace App\AdminBundle\Business\InvoicePayment;

use App\AdminBundle\Business\Base\BaseInlineFormHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;

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

        $payDate = $this->helperCommon->formatKendoDatetime($model['payDate']);

        $payment = $this->helperDoctrine->findOneById('AppClientBundle:ClientPayment', $model['id']);
        $payment->setIdGateway($model['idGateway']);
        $payment->setTransaction($model['transaction']);
        if ($payDate) {
            $payment->setPayDate($payDate);
        }
        $payment->setAmount($model['amount']);
        $payment->setStatus($model['status']);

        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        $this->entity = $payment;

        Business\Invoice\Utils::updateInvoiceStatus($this->container, $payment->getIdInvoice());

        parent::onSuccess();
    }
}