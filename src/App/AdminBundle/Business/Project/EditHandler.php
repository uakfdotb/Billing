<?php

namespace App\AdminBundle\Business\Project;


use App\AdminBundle\Business\Base\BaseEditHandler;

use App\ClientBundle\Entity;


class EditHandler extends BaseEditHandler
{

    public function loadEntity()
    {

        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:ClientProject');

    }

    public function getModelFromEntity($entity)
    {

        $model = new EditModel();

        $this->helperCommon->copyEntityToModel($this->entity, $model);


        return $model;

    }


    public function buildForm($builder)
    {

        $builder->add('name', 'text', array(

            'attr'     => array(

                'placeholder' => 'NAME'

            ),

            'required' => false

        ));

        $builder->add('status', 'choice', array(

            'attr'     => array(

                'placeholder' => 'STATUS'

            ),

            'required' => false,

            'choices'  => $this->helperMapping->getMapping('project_status')

        ));

        $builder->add('idClient', 'choice', array(

            'attr'     => array(

                'placeholder' => 'CLIENT'

            ),

            'required' => false,

            'choices'  => $this->helperMapping->getMapping('client_list')

        ));

        $builder->add('code', 'text', array(

            'attr'     => array(

                'placeholder' => 'CODE'

            ),

            'required' => false

        ));

        $builder->add('budget', 'money', array(

            'attr'     => array(

                'placeholder' => 'BUDGET'

            ),

            'currency' => $this->helperCommon->getConfig()->getBillingCurrency(),

            'required' => false

        ));

        $builder->add('dueDate', 'date_picker', array(

            'attr'     => array(

                'placeholder' => 'DUE_DATE'

            ),

            'widget'   => 'single_text',

            'required' => false

        ));

        $builder->add('idType', 'choice', array(

            'attr'     => array(

                'placeholder' => 'TYPE'

            ),

            'required' => false,

            'choices'  => $this->helperMapping->getMapping('project_type')

        ));


        return $builder;

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();

        $entity = $this->getEntity();


        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $entity);


        parent::onSuccess();

    }

}