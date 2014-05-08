<?php
namespace App\AdminBundle\Business\PhysicalItem;

use App\AdminBundle\Business\Base\BaseEditHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class EditHandler extends BaseEditHandler
{
    public function loadEntity()
    {
        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:PhysicalItem');
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
        $builder->add('brand', 'text', array(
            'attr'     => array(
                'placeholder' => 'BRAND'
            ),
            'required' => true
        ));
        $builder->add('model', 'text', array(
            'attr'     => array(
                'placeholder' => 'MODEL'
            ),
            'required' => true
        ));
        $builder->add('description', 'textarea', array(
            'attr'     => array(
                'placeholder' => 'DESCRIPTION',
                'style'       => 'width: 60%;height: 120px'
            ),
            'required' => true
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