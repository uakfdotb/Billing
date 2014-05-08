<?php

namespace App\AdminBundle\Business\Email;


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

        $builder->add('subject', 'text', array(

            'attr'     => array(

                'placeholder' => 'SUBJECT'

            ),

            'required' => true

        ));

        $builder->add('body', 'texteditor', array(

            'attr'     => array(

                'placeholder' => 'BODY',

                'rows'        => 15,

                'style'       => 'width: 100%'

            ),

            'required' => false

        ));


        return $builder;

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();


        $email = new Entity\Email();

        $email->setName($model->name);

        $email->setSubject($model->subject);

        $email->setBody($model->body);


        $this->entityManager->persist($email);

        $this->entityManager->flush();


        parent::onSuccess();

    }


}