<?php

namespace App\AdminBundle\Business\Account;


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

        return $builder;

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();

        $helperUser = $this->container->get('app_admin.helper.user');


        $account = new Entity\Account();

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $account);

        $account->setBalance(0.00);


        $this->entityManager->persist($account);

        $this->entityManager->flush();


        parent::onSuccess();

    }

}