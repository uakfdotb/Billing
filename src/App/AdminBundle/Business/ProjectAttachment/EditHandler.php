<?php

namespace App\AdminBundle\Business\ProjectAttachment;


use App\AdminBundle\Business\Base\BaseEditHandler;

use App\ClientBundle\Entity;


class EditHandler extends BaseEditHandler
{

    public function loadEntity()
    {

        $id = $this->container->get('request')->query->get('id', 0);

        return $this->helperDoctrine->findOneByid('AppClientBundle:ClientProjectAttachment', $id);

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

        $builder->add('timestamp', 'date_picker', array(

            'attr'     => array(

                'placeholder' => 'DATE'

            ),

            'required' => false

        ));

        $builder->add('description', 'text', array(

            'attr'     => array(

                'placeholder' => 'DESCRIPTION'

            ),

            'required' => true

        ));

        $builder->add('attachments', 'file_attachment', array(

            'attr'     => array(

                'placeholder' => "ATTACHMENTS"

            ),

            'required' => false

        ));

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();

        $entity = $this->getEntity();


        parent::onSuccess();

    }

}