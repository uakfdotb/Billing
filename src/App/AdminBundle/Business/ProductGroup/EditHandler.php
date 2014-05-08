<?php
namespace App\AdminBundle\Business\ProductGroup;

use App\AdminBundle\Business\Base\BaseEditHandler;
use App\ClientBundle\Entity;

class EditHandler extends BaseEditHandler
{
    public function loadEntity()
    {
        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:ProductGroup');
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
        $builder->add('isAvailable', 'checkbox', array(
            'attr'     => array(
                'placeholder' => 'IS_AVAILABLE'
            ),
            'required' => true
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