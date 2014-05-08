<?php
namespace App\AdminBundle\Business\CreditNote;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Entity;

class CreateHandler extends BaseCreateHandler
{
    public function getDefaultValues()
    {
        $model = new CreateModel();

        $model->idClient = $this->container->get('request')->query->get('id', null);

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

        return $builder;
    }

    public function onSuccess()
    {
        $model = $this->getForm()->getData();

        $note = new Entity\ClientCreditNote();
        $note->setIdClient($model->idClient);
        $note->setAmount($model->amount);
        $note->setNote($model->note);

        $this->entityManager->persist($note);
        $this->entityManager->flush();

        parent::onSuccess();
    }
}