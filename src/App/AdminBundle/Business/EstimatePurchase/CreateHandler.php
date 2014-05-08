<?php

namespace App\AdminBundle\Business\EstimatePurchase;


use App\AdminBundle\Business\Base\BaseCreateHandler;

use App\ClientBundle\Entity;


class CreateHandler extends BaseCreateHandler
{

    public function getDefaultValues()
    {

        $model = new CreateModel();


        return $model;

    }


    public function buildForm($builder)
    {

        $builder->add('idEstimate', 'choice', array(

            'attr'     => array(

                'placeholder' => 'ESTIMATE'

            ),

            'required' => true,

            'choices'  => $this->helperMapping->getMapping('estimate_list')

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


        return $builder;

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();


        $purchase = new Entity\ClientEstimatePurchase();

        $purchase->setIdEstimate($model->idEstimate);

        $purchase->setName($model->name);

        $purchase->setPurchaseDate($model->purchaseDate);

        $this->entityManager->persist($purchase);

        $this->entityManager->flush();


        if (is_array($model->attachments)) {

            // Create upload directory

            $randString = $this->container->get('app_admin.helper.common')->generateRandString(128);

            $directory = $this->container->getParameter('estimate_purchase_upload_dir') . '/' . $randString;

            while (is_dir($directory)) {

                $randString = $this->container->get('app_admin.helper.common')->generateRandString(128);

                $directory = $this->container->getParameter('estimate_purchase_upload_dir') . '/' . $randString;

            }

            @mkdir($directory);


            foreach ($model->attachments as $uploadFile) {
                if($this->get('app_admin.helper.disk_quota')->getFreeDiskSpace() < 0)
                    die("You have exceeded your disk quota.");

                $uploadFile->move($directory, $uploadFile->getClientOriginalName());

                $pFile = new Entity\ClientEstimatePurchaseFile();

                $pFile->setIdEstimatePurchase($purchase->getId());

                $pFile->setFileSize($uploadFile->getClientSize() / (1024 * 1024));

                $pFile->setFile($randString . '/' . $uploadFile->getClientOriginalName());

                $this->entityManager->persist($pFile);

            }

            $this->entityManager->flush();

        }


        parent::onSuccess();

    }

}