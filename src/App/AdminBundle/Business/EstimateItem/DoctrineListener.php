<?php

namespace App\AdminBundle\Business\EstimateItem;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use App\ClientBundle\Entity;
use App\ClientBundle\Entity\ClientInvoice;
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
        return array('postRemove', 'postPersist', 'postUpdate');
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Entity\ClientEstimateItem) {
            $this->updateEstimateStatus($entity->getIdEstimate());
        }
    }


    public function postPersist(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();

        if ($entity instanceof Entity\ClientEstimateItem) {

            $this->updateEstimateStatus($entity->getIdEstimate());

        }

    }


    public function postUpdate(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();

        if ($entity instanceof Entity\ClientEstimateItem) {

            $this->updateEstimateStatus($entity->getIdEstimate());

        }

    }


    public function updateEstimateStatus($estimateId)
    {

        $estimate = $this->container->get('doctrine')->getRepository('AppClientBundle:ClientEstimate')->findOneById($estimateId);

        $em = $this->container->get('doctrine')->getEntityManager();


        // Estimate amount

        $query = $em->createQueryBuilder();

        $query->select("SUM(p.quantity * p.unitPrice) as totalAmount")

            ->from('AppClientBundle:ClientEstimateItem', 'p')

            ->andWhere('p.idEstimate = :idEstimate')

            ->setParameter('idEstimate', $estimateId);

        $totalAmount = $query->getQuery()->getSingleScalarResult();

        $discount = round($totalAmount * $estimate->getDiscount(), 2);

        $tax = round(($totalAmount - $discount) * $estimate->getTax(), 2);

        $sumAmount = round($totalAmount - $discount + $tax, 2);


        // Payment

        $sumPayment = 0;

        $query = $this->container->get('doctrine')->getEntityManager()->createQueryBuilder();

        $query->select("SUM(p.amount) as total")

            ->from('AppClientBundle:ClientPayment', 'p')

            ->andWhere('p.idEstimate = :idEstimate')

            ->setParameter('idEstimate', $estimate->getId());

        $sumPayment = $query->getQuery()->getSingleScalarResult();


        // Update status

        if ($totalAmount > 0) {

            if ($sumPayment >= $totalAmount) {

                $estimate->setInvoiceStatus(ClientInvoice::STATUS_PAID);

            } else {

                $estimate->setInvoiceStatus(ClientInvoice::STATUS_UNPAID);

                if ($estimate->getDueDate()) {

                    $today = new \DateTime();

                    if ($estimate->getDueDate()->getTimestamp() < $today->getTimestamp()) {

                        $estimate->setInvoiceStatus(ClientInvoice::STATUS_OVERDUE);

                    }

                }

            }

        }


        $estimate->setTotalAmount($totalAmount);

        $estimate->setTotalPayment($sumPayment);

        $em->persist($estimate);

        $em->flush();

    }

}