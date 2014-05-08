<?php

namespace App\AdminBundle\Business\ProjectAttachment;


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

        $builder->add('name', 'text', array(

            'attr'     => array(

                'placeholder' => 'NAME'

            ),

            'required' => true

        ));

        $builder->add('timestamp', 'date_picker', array(

            'attr'     => array(

                'placeholder' => 'DATE'

            ),

            'required' => false

        ));

        $builder->add('description', 'text', array(

            'attr'     => array(

                'placeholder' => 'DESCRIPTION'

            ),

            'required' => true

        ));

        $builder->add('attachments', 'file_attachment', array(

            'attr'     => array(

                'placeholder' => "ATTACHMENTS"

            ),

            'required' => false

        ));


        return $builder;

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();


        $attachment = new Entity\ClientProjectAttachment();

        $attachment->setIdProject($this->helperDoctrine->getRequestId());

        $attachment->setName($model->name);

        $attachment->setDescription($model->description);

        $attachment->setTimestamp($model->timestamp);

        $this->entityManager->persist($attachment);

        $this->entityManager->flush();


        if (is_array($model->attachments)) {

            // Create upload directory

            $randString = $this->container->get('app_admin.helper.common')->generateRandString(128);

            $directory = $this->container->getParameter('project_upload_dir') . '/' . $randString;

            while (is_dir($directory)) {

                $randString = $container->get('app_admin.helper.common')->generateRandString(128);

                $directory = $this->container->getParameter('project_upload_dir') . '/' . $randString;

            }

            @mkdir($directory);


            foreach ($model->attachments as $uploadFile) {
                if($this->container->get('app_admin.helper.disk_quota')->getFreeDiskSpace() < 0)
                    die("You have exceeded your disk quota.");

                $uploadFile->move($directory, $uploadFile->getClientOriginalName());


                $pFile = new Entity\ClientProjectAttachmentFile();

                $pFile->setIdProjectAttachment($attachment->getId());

                $pFile->setFileSize($uploadFile->getClientSize() / (1024 * 1024));

                $pFile->setFile($randString . '/' . $uploadFile->getClientOriginalName());

                $this->entityManager->persist($pFile);

            }

            $this->entityManager->flush();

        }


        parent::onSuccess();

    }

}