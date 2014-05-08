<?php

namespace App\AdminBundle\Business\Tax;

use App\AdminBundle\Business\Base\BaseEditHandler;
use App\AdminBundle\Business\GlobalUtils;
use App\ClientBundle\Entity;

class EditHandler extends BaseEditHandler
{
    public function loadEntity()
    {
        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:Tax');
    }

    public function getModelFromEntity($entity)
    {
        $model = new EditModel();
        $model->container = $model->container = $this->container;
        $model->entityId = $entity->getId();
        $this->helperCommon->copyEntityToModel($this->entity, $model);

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
    }


    public function onSuccess()
    {
        $model = $this->getForm()->getData();
        $entity = $this->getEntity();

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $entity);

        parent::onSuccess();
    }
}