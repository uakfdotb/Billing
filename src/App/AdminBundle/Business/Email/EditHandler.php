<?php

namespace App\AdminBundle\Business\Email;


use App\AdminBundle\Business\Base\BaseEditHandler;

use App\ClientBundle\Entity;


class EditHandler extends BaseEditHandler
{

    public function loadEntity()
    {

        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:Email');

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

        $builder->add('subject', 'text', array(

            'attr'     => array(

                'placeholder' => 'SUBJECT'

            ),

            'required' => true

        ));

        $builder->add('body', 'texteditor', array(

            'attr'     => array(

                'placeholder' => 'BODY',

                'rows'        => 15,

                'style'       => 'width: 100%'

            ),

            'required' => false

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