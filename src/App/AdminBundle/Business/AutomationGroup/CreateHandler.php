<?php

namespace App\AdminBundle\Business\AutomationGroup;


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

        $builder->add('url', 'text', array(

            'attr'     => array(

                'placeholder' => 'URL'

            ),

            'required' => false

        ));

        /*

        $builder->add('fields', 'choice', array(

            'attr'     => array(

                'placeholder' => 'Fields'

            ),

            'required' => false,

            'choices'  => $this->helperMapping->getMapping('automation_group_field'),

            'multiple' => 'multiple',

            'expanded' => 'expanded'

        ));*/


        return $builder;

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();


        $group = new Entity\AutomationGroup();

        $group->setName($model->name);

        $group->setUrl($model->url);

        $this->entityManager->persist($group);

        $this->entityManager->flush();


        // Save fields

        /*

        foreach ($model->fields as $idField) {

            $groupField = new Entity\AutomationGroupField();

            $groupField->setIdAutomationGroup($group->getId());

            $groupField->setIdProductField($idField);

            $this->entityManager->persist($groupField);

        }

        $this->entityManager->flush();*/


        parent::onSuccess();

    }

}