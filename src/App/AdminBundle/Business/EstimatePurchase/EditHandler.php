<?php

namespace App\AdminBundle\Business\EstimatePurchase;


use App\AdminBundle\Business\Base\BaseEditHandler;

use App\ClientBundle\Entity;


class EditHandler extends BaseEditHandler
{

    public function loadEntity()
    {

        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:ClientEstimatePurchase');

    }

    public function getModelFromEntity($entity)
    {

        $model = new EditModel();

        $this->helperCommon->copyEntityToModel($this->entity, $model);


        return $model;

    }


    public function buildForm($builder)
    {

        $builder->add('idEstimate', 'choice', array(

            'attr'     => array(

                'placeholder' => 'ESTIMATE'

            ),

            'required' => true,

            'choices'  => $this->helperMapping->getMapping('estimate_list')

        ));

        $builder->add('name', 'text', array(

            'attr'     => array(

                'placeholder' => 'NAME'

            ),

            'required' => true

        ));

        $builder->add('purchaseDate', 'date_picker', array(

            'attr'     => array(

                'placeholder' => 'PURCHASE_DATE'

            ),

            'required' => false

        ));

        $builder->add('attachments', 'file_attachment', array(

            'attr'     => array(

                'placeholder' => "INVOICE_PDF"

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