<?php
namespace App\AdminBundle\Business\Client;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class OrderHandler extends BaseCreateHandler
{
    public function getDefaultValues()
    {
        $model = new OrderModel();

        return $model;
    }

    public function buildForm($builder)
    {
        $builder->add('idProduct', 'choice', array(
            'attr'     => array(
                'placeholder' => 'PRODUCT'
            ),
            'required' => true,
            'choices'  => $this->helperMapping->getMapping('product_list')
        ));
        $builder->add('status', 'choice', array(
            'attr'     => array(
                'placeholder' => 'STATUS'
            ),
            'required' => true,
            'choices'  => $this->helperMapping->getMapping('order_status')
        ));
        $builder->add('idOrderPaymentTerm', 'choice', array(
            'attr'     => array(
                'placeholder' => 'ORDER_PAYMENT_TERM'
            ),
            'required' => true,
            'choices'  => $this->helperMapping->getMapping('order_payment_term')
        ));
        return $builder;
    }

    public function onSuccess()
    {
        $model        = $this->getForm()->getData();
        $helperCommon = $this->container->get('app_admin.helper.common');

        $orderNumber = $helperCommon->generateOrderNumber(10);
        $idClient    = $this->container->get('request')->get('idClient', 0);

        $idEstimate = Business\Order\Utils::generateEstimate($this->container, $idClient, $model->idProduct, $model->idOrderPaymentTerm, $orderNumber);

        Business\Order\Utils::decreaseProductStock($this->container, $model->idProduct);

        $order = new Entity\ProductOrder();
        $order->setIdProduct($model->idProduct);
        $order->setIdClient($idClient);
        $order->setStatus($model->status);
        $order->setTimestamp(new \DateTime());
        $order->setIdOrderPaymentTerm($model->idOrderPaymentTerm);
        $order->setOrderNumber($orderNumber);
        $order->setIpAddress($_SERVER['REMOTE_ADDR']);
        $order->setIdEstimate($idEstimate);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        Business\AutomationGroup\Utils::handlePostOrdered($this->container, $order);
        if ($order->getStatus() == Business\Order\Constants::ORDER_STATUS_PAID) {
            Business\AutomationGroup\Utils::handlePostPaid($this->container, $order);
        } else if ($order->getStatus() == Business\Order\Constants::ORDER_STATUS_ACCEPTED) {
            Business\AutomationGroup\Utils::handlePostAccepted($this->container, $order);
        }

        parent::onSuccess();
    }
}