<?php

namespace App\AdminBundle\Business\Recurring;


use App\AdminBundle\Business\Base\BaseEditHandler;

use App\ClientBundle\Entity;


class EditHandler extends BaseEditHandler
{

    public function loadEntity()
    {

        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:ClientRecurring');

    }

    public function getModelFromEntity($entity)
    {

        $model = new EditModel();

        $this->helperCommon->copyEntityToModel($this->entity, $model);


        return $model;

    }


    public function buildForm($builder)
    {

        $builder->add('idClient', 'choice', array(

            'attr'     => array(

                'placeholder' => 'CLIENT'

            ),

            'label' => 'Client',

            'required' => true,

            'choices'  => $this->helperMapping->getMapping('client_list')

        ));

        $builder->add('subject', 'text', array(

            'attr'     => array(

                'placeholder' => 'SUBJECT'

            ),

            'label' => 'Subject',

            'required' => true

        ));

        $builder->add('idSchedule', 'choice', array(

            'attr'     => array(

                'placeholder' => 'SCHEDULE'

            ),

            'label' => 'Schedule',

            'required' => false,

            'choices'  => $this->helperMapping->getMapping('recurring_schedule')

        ));

        $builder->add('nextDue', 'date_picker', array(
            'attr'     => array(
                'placeholder' => 'DUE_DAYS'
            ),
            'widget'   => 'single_text',
            'label' => 'Next Due',
            'required' => false
        ));

        $builder->add('discount', 'percent', array(

            'attr'     => array(

                'placeholder' => 'DISCOUNT'

            ),
            'label' => 'Discount',

            'required' => false

        ));

        $builder->add('tax', 'choice', array(
            'attr'     => array(
                'placeholder' => 'TAX GROUP'
            ),
            'required' => false,
            'label' => 'Tax',
            'choices'  => $this->helperMapping->getMapping('tax_list')
        ));


        $builder->add('notes', 'textarea', array(

            'attr'     => array(

                'placeholder' => 'NOTES'

            ),
            'label' => 'Notes',

            'required' => false

        ));

        $builder->add('isInvoiced', 'choice', array(

            'attr'     => array(

                'placeholder' => 'GENERATE'

            ),
            'label' => 'Generate',

            'required' => true,

            'choices'  => array(1 => 'Invoice')

        ));


        return $builder;

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();

        $entity = $this->getEntity();


        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $entity);


        parent::onSuccess();

    }

}