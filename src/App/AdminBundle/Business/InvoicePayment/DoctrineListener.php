<?php

namespace App\AdminBundle\Business\InvoicePayment;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class DoctrineListener implements \Doctrine\Common\EventSubscriber
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return array('postRemove', 'prePersist', 'preUpdate');
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Entity\ClientPayment) {
            Business\Invoice\Utils::updateInvoiceStatus($this->container, $entity->getIdInvoice());
            $this->removeTransaction($entity);
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Entity\ClientPayment) {
            Business\Invoice\Utils::updateInvoiceStatus($this->container, $entity->getIdInvoice());

            $idTransaction = $this->makeTransaction($entity);
            if (!$idTransaction) {
                $entity->setIdAccountTransaction($idTransaction);
            }
        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Entity\ClientPayment) {
            Business\Invoice\Utils::updateInvoiceStatus($this->container, $entity->getIdInvoice());
        }
    }

    public function removeTransaction($clientPayment)
    {
        $doctrine    = $this->container->get('doctrine');
        $transaction = $doctrine->getRepository('AppClientBundle:AccountTransaction')
            ->findOneById($clientPayment->getIdAccountTransaction());
        if ($transaction) {
            $doctrine->getEntityManager()->remove($transaction);
            $doctrine->getEntityManager()->flush();
        }
    }


    public function makeTransaction($clientPayment)
    {
        $config   = $this->container->get('app_admin.helper.common')->getGatewayConfig();
        $doctrine = $this->container->get('doctrine');
        $em       = $doctrine->getEntityManager();

        $account = false;
        switch ($clientPayment->getIdGateway()) {
            case Business\Common\Constants::GATEWAY_PAYPAL:
                $account = $doctrine->getRepository('AppClientBundle:Account')->findOneById($config->getIdPaypalAccount());
                break;

            case Business\Common\Constants::GATEWAY_BANK_TRANSFER:
                $account = $doctrine->getRepository('AppClientBundle:Account')->findOneById($config->getIdBankAccount());
                break;
        }


        if ($account) {
            $transaction = new Entity\AccountTransaction();
            $transaction->setIdAccount($account->getId());
            $transaction->setTimestamp(new \DateTime());
            $transaction->setDescription($clientPayment->getTransaction());
            if ($clientPayment->getAmount() > 0) {
                $transaction->setIdDirection(Business\Account\Constants::ACCOUNT_DIRECTION_IN);
                $transaction->setAmount($clientPayment->getAmount());
            } else {
                $transaction->setIdDirection(Business\Account\Constants::ACCOUNT_DIRECTION_OUT);
                $transaction->setAmount($clientPayment->getAmount() * -1);
            }

            $em->persist($transaction);

            //$em->flush();

            return $transaction->getId();
        }

        return false;
    }

}

