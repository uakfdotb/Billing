<?php

namespace App\AdminBundle\Business\Supplier;


use App\AdminBundle\Business\Base\BaseCreateHandler;

use App\ClientBundle\Entity;


class CreateHandler extends BaseCreateHandler
{

    public function getDefaultValues()
    {

        $model = new CreateModel();

        $model->container = $this->container;


        return $model;

    }


    public function buildForm($builder)
    {

        $builder->add('firstname', 'text', array(

            'attr'     => array(

                'placeholder' => 'FIRST_NAME'

            ),

            'required' => false

        ));

        $builder->add('lastname', 'text', array(

            'attr'     => array(

                'placeholder' => 'LAST_NAME'

            ),

            'required' => false

        ));

        $builder->add('company', 'text', array(

            'attr'     => array(

                'placeholder' => 'COMPANY'

            ),

            'required' => false

        ));

        $builder->add('address1', 'text', array(

            'attr'     => array(

                'placeholder' => 'ADDRESS_1'

            ),

            'required' => false

        ));

        $builder->add('address2', 'text', array(

            'attr'     => array(

                'placeholder' => 'ADDRESS_2'

            ),

            'required' => false

        ));

        $builder->add('city', 'text', array(

            'attr'     => array(

                'placeholder' => 'CITY'

            ),

            'required' => false

        ));

        $builder->add('state', 'text', array(

            'attr'     => array(

                'placeholder' => 'STATE'

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

        $builder->add('phone', 'text', array(

            'attr'     => array(

                'placeholder' => 'PHONE'

            ),

            'required' => false

        ));

        $builder->add('email', 'text', array(

            'attr'     => array(

                'placeholder' => 'EMAIL'

            ),

            'required' => false

        ));

        return $builder;

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();

        $helperUser = $this->container->get('app_admin.helper.user');


        $supplier = new Entity\Supplier();

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $supplier);

        //$supplier->setPassword($helperUser->encodePassword($model->password));

        $supplier->setStatus(0);


        $this->entityManager->persist($supplier);

        $this->entityManager->flush();


        parent::onSuccess();

    }

}