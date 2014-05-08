<?php

namespace App\AdminBundle\Business\Account;


use App\AdminBundle\Business\Base\BaseEditHandler;

use App\ClientBundle\Entity;


class EditHandler extends BaseEditHandler
{

    public function loadEntity()
    {

        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:Account');

    }

    public function getModelFromEntity($entity)
    {

        $model = new EditModel();

        $model->container = $this->container;

        $model->entityId = $entity->getId();


        $this->helperCommon->copyEntityToModel($this->entity, $model);


        return $model;

    }


    public function buildForm($builder)
    {

        $builder->add('name', 'text', array(

            'attr'     => array(

                'placeholder' => 'ACCOUNT_NAME'

            ),

            'required' => true

        ));

        $builder->add('number', 'text', array(

            'attr'     => array(

                'placeholder' => 'ACCOUNT_NUMBER'

            ),

            'required' => true

        ));

        $builder->add('sortCode', 'text', array(

            'attr'     => array(

                'placeholder' => 'SORT_CODE'

            ),

            'required' => false

        ));

        $builder->add('idAccountType', 'choice', array(

            'attr'     => array(

                'placeholder' => 'ACCOUNT_TYPE'

            ),

            'required' => false,

            'choices'  => $this->helperMapping->getMapping('account_type')

        ));


        $builder->add('street1', 'text', array(

            'attr'     => array(

                'placeholder' => 'STREET_1'

            ),

            'required' => false

        ));

        $builder->add('street2', 'text', array(

            'attr'     => array(

                'placeholder' => 'STREET_2'

            ),

            'required' => false

        ));

        $builder->add('county', 'text', array(

            'attr'     => array(

                'placeholder' => 'COUNTY'

            ),

            'required' => false

        ));

        $builder->add('town', 'text', array(

            'attr'     => array(

                'placeholder' => 'TOWN'

            ),

            'required' => false

        ));

        $builder->add('postcode', 'text', array(

            'attr'     => array(

                'placeholder' => 'POST_CODE'

            ),

            'required' => false

        ));

        $builder->add('idCountry', 'choice', array(

            'attr'     => array(

                'placeholder' => 'COUNTRY'

            ),

            'required' => false,

            'choices'  => $this->helperMapping->getMapping('country')

        ));


        $builder->add('telephone', 'text', array(

            'attr'     => array(

                'placeholder' => 'TELEPHONE'

            ),

            'required' => false

        ));

        $builder->add('email', 'text', array(

            'attr'     => array(

                'placeholder' => 'EMAIL'

            ),

            'required' => false

        ));

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();

        $entity = $this->getEntity();


        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $entity);


        parent::onSuccess();

    }

}