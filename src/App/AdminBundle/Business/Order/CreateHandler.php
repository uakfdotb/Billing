<?php
namespace App\AdminBundle\Business\Order;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Controller\ProductController__JMSInjector;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;
use App\AdminBundle\Business\Product\Constants as ProductConstants;

class CreateHandler extends BaseCreateHandler
{
    public function getDefaultValues()
    {
        $model = new CreateModel();

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
        $builder->add('idClient', 'choice', array(
            'attr'     => array(
                'placeholder' => 'CLIENT'
            ),
            'required' => true,
            'choices'  => $this->helperMapping->getMapping('client_list')
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
        $model = $this->getForm()->getData();
        $helperCommon = $this->container->get('app_admin.helper.common');

        $orderNumber = $helperCommon->generateOrderNumber(10);

        $idInvoice = Utils::generateInvoice($this->container, $model->idClient, $model->idProduct, $model->idOrderPaymentTerm, $orderNumber);

        Utils::decreaseProductStock($this->container, $model->idProduct);

        $order = new Entity\ProductOrder();
        $order->setIdProduct($model->idProduct);
        $order->setIdClient($model->idClient);
        $order->setStatus($model->status);
        $order->setTimestamp(new \DateTime());
        $order->setOrderNumber($orderNumber);
        $order->setIpAddress($_SERVER['REMOTE_ADDR']);
        $order->setIdInvoice($idInvoice);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        // Handle automation
        $product = $this->container->get('doctrine')->getRepository('AppClientBundle:Product')->findOneById($model->idProduct);
        if($product->getTriggerCreate() == Constants::CREATE_ON_ORDER){
            $productType = ProductConstants::getProductTypes()[$product->getType()];
            $class = sprintf('\\App\\AdminBundle\\Helper\\Provision\\%s', $productType);
            call_user_func(array($class, 'createAccount'));
        }

        parent::onSuccess();
    }
}