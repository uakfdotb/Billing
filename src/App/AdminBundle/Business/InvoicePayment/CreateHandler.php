<?php
namespace App\AdminBundle\Business\InvoicePayment;

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

        $payDate = $this->helperCommon->formatKendoDatetime($model['payDate']);

        $payment = new Entity\ClientPayment();
        $payment->setIdInvoice($this->container->get('request')->query->get('id', 0));
        $payment->setIdGateway($model['idGateway']);
        $payment->setTransaction($model['transaction']);
        $payment->setPayDate($payDate);
        $payment->setAmount($model['amount']);
        $payment->setStatus($model['status']);
        $payment->setFee(0);

        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        $this->newEntity = $payment;


        Business\Invoice\Utils::updateInvoiceStatus($this->container, $payment->getIdInvoice());

        parent::onSuccess();
    }
}
