<?php

namespace App\AdminBundle\Business\InvoiceItem;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
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
        return array('postRemove', 'postPersist', 'postUpdate');
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Entity\ClientInvoiceItem) {
            Business\Invoice\Utils::updateInvoiceStatus($this->container, $entity->getIdInvoice());
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Entity\ClientInvoiceItem) {
            Business\Invoice\Utils::updateInvoiceStatus($this->container, $entity->getIdInvoice());
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Entity\ClientInvoiceItem) {
            Business\Invoice\Utils::updateInvoiceStatus($this->container, $entity->getIdInvoice());
        }
    }
}