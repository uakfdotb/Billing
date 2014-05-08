<?php

namespace App\AdminBundle\Business\AutomationGroup;


use App\AdminBundle\Business\Base\BaseEditHandler;

use App\ClientBundle\Entity;


class EditHandler extends BaseEditHandler
{

    public function loadEntity()
    {

        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:AutomationGroup');

    }


    public function getModelFromEntity($entity)
    {

        $model = new EditModel();

        $model->name = $entity->getName();

        $model->url = $entity->getUrl();

        //$model->fields = $this->helperDoctrine->loadList('AppClientBundle:AutomationGroupField', 'idAutomationGroup', 'idProductField', $entity->getId());


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

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();

        $entity = $this->getEntity();


        $entity->setName($model->name);

        $entity->setUrl($model->url);


        // Save fields

        /*

        $this->helperDoctrine->deleteList('AppClientBundle:AutomationGroupField', 'idAutomationGroup', $entity->getId());

        

        foreach ($model->fields as $idField) {

            $groupField = new Entity\AutomationGroupField();

            $groupField->setIdAutomationGroup($entity->getId());

            $groupField->setIdProductField($idField);

            $this->entityManager->persist($groupField);

        }

        $this->entityManager->flush();

        */


        parent::onSuccess();

    }

}