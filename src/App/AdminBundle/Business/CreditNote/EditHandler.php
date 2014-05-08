<?php
namespace App\AdminBundle\Business\CreditNote;

use App\AdminBundle\Business\Base\BaseEditHandler;
use App\ClientBundle\Entity;

class EditHandler extends BaseEditHandler
{
    public function loadEntity()
    {
        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:ClientCreditNote');
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
            'required' => false,
            'choices'  => $this->helperMapping->getMapping('client_list')
        ));
        $builder->add('amount', 'money', array(
            'attr'     => array(
                'placeholder' => 'AMOUNT'
            ),
            'currency' => $this->helperCommon->getConfig()->getBillingCurrency(),
            'required' => false
        ));
        $builder->add('note', 'textarea', array(
            'attr'     => array(
                'placeholder' => 'NOTE',
                'rows'        => 15,
                'style'       => 'width: 70%'
            ),
            'required' => false
        ));
    }

    public function onSuccess()
    {
        $model  = $this->getForm()->getData();
        $entity = $this->getEntity();

        $entity->setIdClient($model->idClient);
        $entity->setAmount($model->amount);
        $entity->setNote($model->note);

        parent::onSuccess();
    }
}