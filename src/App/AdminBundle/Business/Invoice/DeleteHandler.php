<?php

namespace App\AdminBundle\Business\Invoice;

use App\AdminBundle\Business\Base\BaseDeleteHandler;
use App\ClientBundle\Entity;

class DeleteHandler extends BaseDeleteHandler
{
    public function execute()
    {
        $model = $this->getModel();

        // Cascade through invoice payments and items
        $em = $this->container->get('doctrine')->getEntityManager();
        $items = $em->getRepository('AppClientBundle:ClientInvoiceItem')->findByIdInvoice($model['id']);
        $payments = $em->getRepository('AppClientBundle:ClientPayment')->findByIdInvoice($model['id']);

        foreach($items as $item) $em->remove($item);
        foreach($payments as $payment) $em->remove($payment);
        $em->flush();

        // Delete the invoice
        $this->helperDoctrine->deleteOneById('AppClientBundle:ClientInvoice', $model['id']);
    }
}

