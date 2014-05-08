<?php
namespace App\AdminBundle\Helper;

class DiskQuota
{
    public $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFreeDiskSpace()
    {
        $em = $this->container->get('doctrine')->getEntityManager();

        $query1 = $em->createQueryBuilder();
        $query1->select("SUM(p.fileSize)")
            ->from("AppClientBundle:ClientEstimatePurchaseFile", "p")
        ;

        $query2 = $em->createQueryBuilder();
        $query2->select("SUM(p.fileSize)")
            ->from("AppClientBundle:ClientInvoicePurchaseFile", "p")
        ;

        $query3 = $em->createQueryBuilder();
        $query3->select("SUM(p.fileSize)")
            ->from("AppClientBundle:ClientProjectAttachmentFile", "p")
        ;

        $query4 = $em->createQueryBuilder();
        $query4->select("SUM(p.fileSize)")
            ->from("AppClientBundle:SupplierPurchaseFile", "p")
        ;

        $query5 = $em->createQueryBuilder();
        $query5->select("SUM(p.fileSize)")
            ->from("AppClientBundle:TicketResponseFile", "p")
        ;

        return $this->container->get('app_admin.helper.common')->getCurrentTenant()->getDiskQuota()
            - ($query1->getQuery()->getSingleScalarResult()
            + $query2->getQuery()->getSingleScalarResult()
            + $query3->getQuery()->getSingleScalarResult()
            + $query4->getQuery()->getSingleScalarResult()
            + $query5->getQuery()->getSingleScalarResult())
            ;
    }

}