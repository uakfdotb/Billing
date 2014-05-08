<?php

namespace App\AdminBundle\Business\Project;


use App\AdminBundle\Business\Base\BaseCreateHandler;

use App\ClientBundle\Entity;


class CreateHandler extends BaseCreateHandler
{

    public function getDefaultValues()
    {

        $model = new CreateModel();

        $model->idClient = $this->container->get('request')->query->get('id', null);


        return $model;

    }


    public function buildForm($builder)
    {
        $builder->add('idClient', 'choice', array(

            'attr'     => array(

                'placeholder' => 'CLIENT'

            ),

            'required' => false,

            'choices'  => $this->helperMapping->getMapping('client_list')

        ));

        return $builder;

    }


    public function onSuccess()
    {
        $model = $this->getForm()->getData();


        $project = new Entity\ClientProject();

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $project);


        $this->entityManager->persist($project);

        $this->entityManager->flush();


        parent::onSuccess();

    }

}