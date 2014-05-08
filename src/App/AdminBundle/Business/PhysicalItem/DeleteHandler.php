<?php
namespace App\AdminBundle\Business\PhysicalItem;

use App\AdminBundle\Business\Base\BaseDeleteHandler;
use App\ClientBundle\Entity;

class DeleteHandler extends BaseDeleteHandler
{
    public function execute()
    {
        $model = $this->getModel();

        // Cascade through item purhchses and sales
        $em = $this->container->get('doctrine')->getEntityManager();
        $sales = $em->getRepository('AppClientBundle:PhysicalItemSold')->findByIdItem($model['id']);
        $purchases = $em->getRepository('AppClientBundle:PhysicalItemPurchase')->findByIdItem($model['id']);

        foreach($sales as $sale) $em->remove($sale);
        foreach($purchases as $purchase) $em->remove($purchase);
        $em->flush();

        // Delete the invoice
        $this->helperDoctrine->deleteOneById('AppClientBundle:ClientInvoice', $model['id']);
    }
}
