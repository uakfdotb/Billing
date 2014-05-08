<?php
namespace App\ClientBundle\Business\Order;

use App\AdminBundle\Business as AdminBusiness;
use App\ClientBundle\Entity;

class CreateHandler extends AdminBusiness\Base\BaseCreateHandler
{
    public $product = null;

    public function getDefaultValues()
    {
        $model            = new CreateModel();
        $model->container = $this->container;
        $model->pid       = $this->container->get('request')->query->get('pid', 0);
        $this->product    = $this->container->get('doctrine')->getRepository('AppClientBundle:Product')->findOneById($model->pid);

        return $model;
    }

    public function buildForm($builder)
    {
        $builder->add('pid', 'hidden');
        $builder->add('idOrderPaymentTerm', 'choice', array(
            'attr'     => array(
                'placeholder' => 'PAYMENT_TERM'
            ),
            'required' => true,
            'choices'  => AdminBusiness\Order\Utils::getPaymentTerms($this->product)
        ));
        return $builder;
    }

    public function onSuccess()
    {
        $helperUser = $this->container->get('app_admin.helper.user');
        $config     = $this->container->get('app_admin.helper.common')->getConfig();
        $idClient   = $helperUser->getUserSession()->getId();
        $idProduct  = $this->container->get('request')->query->get('pid', 0);

        $model        = $this->getForm()->getData();
        $helperCommon = $this->container->get('app_admin.helper.common');

        // Check maxmind
        if ($config->getMaxmindEnabled()) {
            $maxmindResult = AdminBusiness\Order\Utils::checkMaxMind($this->container, $idClient);
            $maxmindRaw    = $maxmindResult['raw'];
            $maxmindResult = $maxmindResult['result'];

            $error = $maxmindResult['err'];
            if (!empty($error)) {
                parent::onSuccess();
                $this->errors[] = $error;
                $this->messages = array();
                return false;
            }

            $isFraudulent = false;
            $score        = isset($maxmindResult['riskScore']) ? $maxmindResult['riskScore'] : false;
            if ($score > $config->getMaxmindRiskScoreThreshold()) {
                $isFraudulent = true;
            }
        } else {
            $isFraudulent = false;
            $maxmindRaw   = 'status=Not checking';
        }

        // Process
        $orderNumber = $helperCommon->generateOrderNumber(10);

        $idEstimate = AdminBusiness\Order\Utils::generateEstimate($this->container, $idClient, $idProduct, $model->idOrderPaymentTerm, $orderNumber);

        $order = new Entity\ProductOrder();
        $order->setIdProduct($idProduct);
        $order->setIdClient($idClient);
        $order->setStatus(AdminBusiness\Order\Constants::ORDER_STATUS_OPEN);
        if ($isFraudulent) {
            $order->setStatus(AdminBusiness\Order\Constants::ORDER_STATUS_FRAUDULENT);
        }
        $order->setTimestamp(new \DateTime());
        $order->setIdOrderPaymentTerm($model->idOrderPaymentTerm);
        $order->setOrderNumber($orderNumber);
        $order->setIpAddress($_SERVER['REMOTE_ADDR']);
        $order->setIdEstimate($idEstimate);
        $order->setMaxmindData($maxmindRaw);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        AdminBusiness\Order\Utils::decreaseProductStock($this->container, $idProduct);

        AdminBusiness\AutomationGroup\Utils::handlePostOrdered($this->container, $order);
        ////////////////////////////////////

        parent::onSuccess();

        $this->messages = array('The order has been placed! Thank you!');
    }
}