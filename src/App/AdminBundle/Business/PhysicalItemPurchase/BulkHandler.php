<?php
namespace App\AdminBundle\Business\PhysicalItemPurchase;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Entity;

class BulkHandler extends BaseCreateHandler
{
    public function getDefaultValues()
    {
        $model = new BulkModel();

        return $model;
    }

    public function buildForm($builder)
    {
        $builder->add('idSupplier', 'choice', array(
            'attr'     => array(
                'placeholder' => 'SUPPLIER'
            ),
            'required' => true,
            'choices'  => $this->helperMapping->getMapping('supplier_list')
        ));
        $builder->add('dateIn', 'datetime_picker', array(
            'attr'     => array(
                'placeholder' => 'DATE_IN'
            ),
            'required' => false
        ));
        $builder->add('purchasePrice', 'money', array(
            'attr'     => array(
                'placeholder' => 'PRICE'
            ),
            'currency' => $this->helperCommon->getConfig()->getBillingCurrency(),
            'required' => false
        ));
        $builder->add('serialNumbers', 'textarea', array(
            'attr'     => array(
                'placeholder' => 'SERIAL_NUMBERS',
                'rows'        => 5, 'cols' => 70
            ),
            'required' => false
        ));
        return $builder;
    }

    public function onSuccess()
    {
        $model      = $this->getForm()->getData();
        $helperUser = $this->container->get('app_admin.helper.user');

        $lines = explode("\n", $model->serialNumbers);
        foreach ($lines as $serialNumber) {
            $serialNumber = trim($serialNumber);

            $purchase = new Entity\PhysicalItemPurchase();
            $purchase->setIdPhysicalItem($this->container->get('request')->query->get('id', 0));
            $purchase->setIdSupplier($model->idSupplier);
            $purchase->setDateIn($model->dateIn);
            $purchase->setPurchasePrice($model->purchasePrice);
            $purchase->setQuantity(1);
            $purchase->setSerialNumber($serialNumber);

            $this->entityManager->persist($purchase);
        }
        $this->entityManager->flush();

        parent::onSuccess();
    }
}