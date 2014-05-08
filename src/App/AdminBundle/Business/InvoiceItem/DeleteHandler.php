<?php

namespace App\AdminBundle\Business\InvoiceItem;


use App\AdminBundle\Business\Base\BaseDeleteHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class DeleteHandler extends BaseDeleteHandler
{
    public function execute()
    {
        $item      = $this->helperDoctrine->findOneByRequestId('AppClientBundle:ClientInvoiceItem');
        $idInvoice = $item->getIdInvoice();

        $this->helperDoctrine->deleteOneByRequestId('AppClientBundle:ClientInvoiceItem');

        Business\Invoice\Utils::updateInvoiceStatus($this->container, $idInvoice);
    }
}

