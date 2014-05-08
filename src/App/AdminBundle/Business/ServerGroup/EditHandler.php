<?php
namespace App\AdminBundle\Business\ServerGroup;

use App\AdminBundle\Business\Base\BaseEditHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class EditHandler extends BaseEditHandler
{
    public function loadEntity()
    {
        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:ServerGroup');
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
        $entity = $this->getEntity();

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $entity);

        parent::onSuccess();
    }
}