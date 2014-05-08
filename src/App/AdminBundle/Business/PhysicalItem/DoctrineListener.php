<?php
namespace App\AdminBundle\Business\PhysicalItem;
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
        if ($entity instanceof Entity\PhysicalItemPurchase ||
            $entity instanceof Entity\PhysicalItemSold
        ) {
            $this->updateStock($entity->getIdPhysicalItem());
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Entity\PhysicalItemPurchase ||
            $entity instanceof Entity\PhysicalItemSold
        ) {
            $this->updateStock($entity->getIdPhysicalItem());
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Entity\PhysicalItemPurchase ||
            $entity instanceof Entity\PhysicalItemSold
        ) {
            $this->updateStock($entity->getIdPhysicalItem());
        }
    }

    public function updateStock($idPhysicalItem)
    {
        $physicalItem = $this->container->get('doctrine')->getRepository('AppClientBundle:PhysicalItem')->findOneById($idPhysicalItem);
        $em           = $this->container->get('doctrine')->getEntityManager();

        // Purchase amount
        $query = $em->createQueryBuilder();
        $query->select("SUM(p.quantity) as totalAmount")
            ->from('AppClientBundle:PhysicalItemPurchase', 'p')
            ->andWhere('p.idPhysicalItem = :idPhysicalItem')
            ->setParameter('idPhysicalItem', $idPhysicalItem);
        $purchaseAmount = $query->getQuery()->getSingleScalarResult();

        // Sold amount
        $query = $em->createQueryBuilder();
        $query->select("SUM(p.quantity) as totalAmount")
            ->from('AppClientBundle:PhysicalItemSold', 'p')
            ->andWhere('p.idPhysicalItem = :idPhysicalItem')
            ->setParameter('idPhysicalItem', $idPhysicalItem);
        $soldAmount = $query->getQuery()->getSingleScalarResult();

        $physicalItem->setStock($purchaseAmount - $soldAmount);
        $em->persist($physicalItem);
        $em->flush();
    }
}