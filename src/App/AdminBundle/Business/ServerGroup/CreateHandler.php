<?php
namespace App\AdminBundle\Business\ServerGroup;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;

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
        $builder->add('description', 'text', array(
            'attr'     => array(
                'placeholder' => 'DESCRIPTION'
            ),
            'required' => false
        ));
        $builder->add('type', 'choice', array(
            'attr'     => array(
                'placeholder' => 'TYPE'
            ),
            'required' => true,
            'choices'  => $this->helperMapping->getMapping('server_group_types')
        ));
        $builder->add('choiceLogic', 'choice', array(
            'attr'     => array(
                'placeholder' => 'CHOICE_LOGIC'
            ),
            'required' => true,
            'choices'  => $this->helperMapping->getMapping('server_group_logic')
        ));
        $builder->add('primary', 'choice', array(
            'attr'     => array(
                'placeholder' => 'PRIMARY_SERVER'
            ),
            'required' => false,
            'choices'  => $this->helperMapping->getMapping('server_list')
        ));

        return $builder;
    }

    public function onSuccess()
    {
        $model  = $this->getForm()->getData();
        $mcrypt = $this->container->get('app_admin.helper.mcrypt');

        $item = new Entity\ServerGroup();
        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $item);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        parent::onSuccess();
    }
}