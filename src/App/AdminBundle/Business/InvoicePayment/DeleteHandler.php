<?php
namespace App\AdminBundle\Business\InvoicePayment;

use App\AdminBundle\Business\Base\BaseDeleteHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class DeleteHandler extends BaseDeleteHandler
{
    public function execute()
    {
        $model = $this->container->get('request')->query->get('models');
        $model = $model[0];

        $payment   = $this->helperDoctrine->findOnebyId('AppClientBundle:ClientPayment', $model['id']);
        $idInvoice = $payment->getIdInvoice();
        $this->helperDoctrine->deleteOneById('AppClientBundle:ClientPayment', $model['id']);

        Business\Invoice\Utils::updateInvoiceStatus($this->container, $idInvoice);
    }
}
