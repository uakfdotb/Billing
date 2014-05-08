<?php

namespace App\AdminBundle\Business\PermissionGroup;


use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\AdminBundle\Exception\MaximumClientsException;
use App\ClientBundle\Entity;
use App\ClientBundle\Entity\PermissionGroup;

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

        $builder->add('permissions', 'choice', array(
            'label'       => 'Permissions',
            'empty_value' => false,
            'choices'     => Constants::getPermissions(),
            'required'    => false,
            'multiple'    => true
        ));
        return $builder;
    }

    public function onSuccess()
    {
        $model = $this->getForm()->getData();

        $group = new Entity\PermissionGroup();
        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $group);

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        parent::onSuccess();
    }
}