<?php
namespace App\AdminBundle\Business\ProductGroup;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Entity;

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
        $builder->add('isAvailable', 'checkbox', array(
            'attr'     => array(
                'placeholder' => 'IS_AVAILABLE'
            ),
            'required' => true
        ));

        return $builder;
    }

    public function onSuccess()
    {
        $model = $this->getForm()->getData();

        $group = new Entity\ProductGroup();
        $group->setName($model->name);

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        parent::onSuccess();
    }
}