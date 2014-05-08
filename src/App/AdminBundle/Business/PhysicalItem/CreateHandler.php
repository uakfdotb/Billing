<?php
namespace App\AdminBundle\Business\PhysicalItem;

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
        $model = $this->getForm()->getData();

        $item = new Entity\PhysicalItem();
        $item->setStock(0);
        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $item);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        parent::onSuccess();
    }
}