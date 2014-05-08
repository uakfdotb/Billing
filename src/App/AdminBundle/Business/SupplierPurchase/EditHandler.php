<?php

namespace App\AdminBundle\Business\SupplierPurchase;


use App\AdminBundle\Business\Base\BaseEditHandler;

use App\ClientBundle\Entity;


class EditHandler extends BaseEditHandler
{

    public function loadEntity()
    {

        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:SupplierPurchase');

    }

    public function getModelFromEntity($entity)
    {

        $model = new EditModel();

        $this->helperCommon->copyEntityToModel($this->entity, $model);


        $transaction = $this->helperDoctrine->findOneById('AppClientBundle:AccountTransaction', $entity->getIdAccountTransaction());

        if ($transaction) {

            $model->amount = $transaction->getAmount();

        }


        return $model;

    }


    public function buildForm($builder)
    {

        $builder->add('idSupplier', 'choice', array(

            'attr'     => array(

                'placeholder' => 'SUPPLIER'

            ),

            'choices'  => $this->helperMapping->getMapping('supplier_list'),

            'required' => true

        ));

        $builder->add('name', 'text', array(

            'attr'     => array(

                'placeholder' => 'NAME'

            ),

            'required' => true

        ));

        $builder->add('purchaseDate', 'date_picker', array(

            'attr'     => array(

                'placeholder' => 'PURCHASE_DATE'

            ),

            'required' => false

        ));


        $builder->add('attachments', 'file_attachment', array(

            'attr'     => array(

                'placeholder' => "INVOICE_PDF"

            ),

            'required' => false

        ));

        $builder->add('amount', 'money', array(

            'attr'     => array(

                'placeholder' => "AMOUNT"

            ),

            'currency' => $this->helperCommon->getConfig()->getBillingCurrency(),

            'required' => true

        ));

        $builder->add('nominalCode', 'choice', array(

            'attr'     => array(

                'placeholder' => 'NOMINAL_CODE'

            ),

            'choices'  => Constants::getNominalCodes(),

            'required' => false

        ));

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();

        $entity = $this->getEntity();


        $entity->setIdSupplier($model->idSupplier);

        $entity->setName($model->name);

        $entity->setPurchaseDate($model->purchaseDate);

        $entity->setAmount($model->amount);

        // Update transaction

        $transaction = $this->helperDoctrine->findOneById('AppClientBundle:AccountTransaction', $entity->getIdAccountTransaction());

        if ($transaction) {

            $transaction->setAmount($model->amount);

            $this->entityManager->persist($transaction);

            $this->entityManager->flush();

        }


        parent::onSuccess();

    }

}