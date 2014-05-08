<?php

namespace App\AdminBundle\Business\Tax;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business\GlobalUtils;

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
                'placeholder' => 'NAME'
            ),
            'required' => true
        ));
        $builder->add('value', 'percent', array(
            'attr'     => array(
                'placeholder' => 'RATE'
            ),
            'required' => true
        ));
        $builder->add('countries', 'choice', array(
            'attr'     => array(
                'placeholder' => 'COUNTRIES'
            ),
            'empty_value' => false,
            'choices'     => GlobalUtils::getAllCountries(),
            'required'    => false,
            'multiple'    => true
        ));

        return $builder;
    }


    public function onSuccess()
    {
        $model = $this->getForm()->getData();

        $tax = new Entity\Tax();
        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $tax);

        $this->entityManager->persist($tax);
        $this->entityManager->flush();

        parent::onSuccess();
    }
}