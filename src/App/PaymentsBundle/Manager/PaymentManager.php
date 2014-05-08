<?php

namespace App\PaymentsBundle\Manager;

use App\PaymentsBundle\Entity\PaymentGateway;
use Doctrine\ORM\EntityManager;
use App\ClientBundle\Entity\ClientInvoice;

class PaymentManager
{
    protected $standardGateways = ['Paypal', 'GoCardless', 'Stripe'];
    
    /** @var \AppKernel */
    protected $kernel;

    /** @var \Doctrine\ORM\EntityManager */
    protected $entityManager;

    protected $repo;

    public function __construct(\AppKernel $kernel, EntityManager $em)
    {
        $this->kernel        = $kernel;
        $this->entityManager = $em;
        $this->repo          = $this->entityManager->getRepository('AppPaymentsBundle:PaymentGateway');

        // we do it in the constructor mainly because we are using it everytime.
        $this->gatewayData   = $this->getPaymentGateways();
    }

    private function xml2array($xml)
    {
        $out = (array)$xml;
        foreach ($out as $k => $v) {
            if (is_object($v)) {
                $out[$k] = $this->xml2array($v);
            }
            if (is_array($v)) {
                foreach ($v as $_k => $_v) {
                    $out[$k][$_k] = $this->xml2array($_v);
                }
            }
        }
        return $out;
    }

    /**
     * @return array
     */
    public function getPaymentGateways()
    {
        $standardGateways = array();
        foreach($this->standardGateways as $s)
        {
            $class = sprintf('\\App\\AdminBundle\\Helper\\Gateway\\%s', $s);
            $standardGateways[] = call_user_func(array($class, 'credentialsDataFormat'));
        }

        return $standardGateways;
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getGateway($type)
    {
        foreach ($this->gatewayData as $gateway) {
            if ($gateway['gateway_type'] == $type) {
                return $gateway;
            }
        }
    }

    /**
     * This returns the gateways mapped by {type} => {name}, because the order might change, and we need to recognize them as type.
     * @return array
     */
    public function getGatewayNamesChoices()
    {
        $names = [];
        foreach ($this->gatewayData as $gateway) {
            $names[$gateway['gateway_type']] = $gateway['name'];
        }

        return $names;
    }

    public function save(PaymentGateway $paymentGateway) {
        $gateway = $this->getGateway($paymentGateway->getType());
        $paymentGateway->setName($gateway['name']);
        $this->entityManager->persist($paymentGateway);
        $this->entityManager->flush();
    }

    public function remove(PaymentGateway $paymentGateway) {
        $this->redact($paymentGateway);
        $this->entityManager->remove($paymentGateway);
        $this->entityManager->flush();
    }
}