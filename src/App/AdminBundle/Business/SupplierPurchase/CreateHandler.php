<?php

namespace App\AdminBundle\Business\SupplierPurchase;


use App\AdminBundle\Business\Base\BaseCreateHandler;

use App\ClientBundle\Entity;
use App\AdminBundle\Business;


class CreateHandler extends BaseCreateHandler
{

    public function getDefaultValues()
    {

        $model = new CreateModel();


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

        $builder->add('idAccount', 'choice', array(

            'attr'     => array(

                'placeholder' => "ACCOUNT"

            ),

            'choices'  => $this->helperMapping->getMapping('account_list'),

            'required' => true

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


        return $builder;

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();


        // Make a transaction

        $transaction = new Entity\AccountTransaction();

        $transaction->setIdAccount($model->idAccount);

        $transaction->setTimestamp(new \DateTime());

        $transaction->setAmount($model->amount);

        $transaction->setIdDirection(Business\Account\Constants::ACCOUNT_DIRECTION_OUT);

        $transaction->setDescription('Purchase: ' . $model->name);

        $this->entityManager->persist($transaction);

        $this->entityManager->flush();


        $purchase = new Entity\SupplierPurchase();

        $purchase->setIdSupplier($model->idSupplier);

        $purchase->setName($model->name);

        $purchase->setPurchaseDate($model->purchaseDate);

        $purchase->setIdAccountTransaction($transaction->getId());

        $this->entityManager->persist($purchase);

        $this->entityManager->flush();


        // Process upload

        if (is_array($model->attachments)) {

            $randString = $this->container->get('app_admin.helper.common')->generateRandString(128);

            $directory = $this->container->getParameter('supplier_upload_dir') . '/' . $randString;

            while (is_dir($directory)) {

                $randString = $container->get('app_admin.helper.common')->generateRandString(128);

                $directory = $this->container->getParameter('supplier_upload_dir') . '/' . $randString;

            }

            @mkdir($directory);


            foreach ($model->attachments as $uploadFile) {
                if($this->container->get('app_admin.helper.disk_quota')->getFreeDiskSpace() < 0)
                    die("You have exceeded your disk quota.");

                $uploadFile->move($directory, $uploadFile->getClientOriginalName());


                $pFile = new Entity\SupplierPurchaseFile();

                $pFile->setIdPurchase($purchase->getId());

                $pFile->setFileSize($uploadFile->getClientSize() / (1024 * 1024));

                $pFile->setFile($randString . '/' . $uploadFile->getClientOriginalName());

                $this->entityManager->persist($pFile);

            }

            $this->entityManager->flush();

        }


        parent::onSuccess();

    }

}