<?php

namespace App\AdminBundle\Business\Account;


use App\AdminBundle\Business\Base\BaseCreateHandler;

use App\ClientBundle\Entity;


class TransferHandler extends BaseCreateHandler
{

    public function getDefaultValues()
    {

        $model = new TransferModel();


        return $model;

    }


    public function buildForm($builder)
    {

        $builder->add('idAccountOut', 'choice', array(

            'attr'     => array(

                'placeholder' => 'ACCOUNT_OUT'

            ),

            'choices'  => $this->helperMapping->getMapping('account_list'),

            'required' => true

        ));

        $builder->add('idAccountIn', 'choice', array(

            'attr'     => array(

                'placeholder' => 'ACCOUNT_IN'

            ),

            'choices'  => $this->helperMapping->getMapping('account_list'),

            'required' => true

        ));


        $builder->add('amount', 'money', array(

            'attr'     => array(

                'placeholder' => 'AMOUNT'

            ),

            'currency' => $this->helperCommon->getConfig()->getBillingCurrency(),

            'required' => true

        ));

        return $builder;

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();


        $repository = $this->container->get('doctrine')->getRepository('AppClientBundle:Account');

        $accountOut = $repository->findOneById($model->idAccountOut);

        $accountIn = $repository->findOneById($model->idAccountIn);


        $out = new Entity\AccountTransaction();

        $out->setIdAccount($model->idAccountOut);

        $out->setIdDirection(Constants::ACCOUNT_DIRECTION_OUT);

        $out->setTimestamp(new \DateTime());

        $out->setAmount($model->amount);

        $out->setDescription('Transfer to account ' . $accountIn->getNumber());

        $this->entityManager->persist($out);


        $in = new Entity\AccountTransaction();

        $in->setIdAccount($model->idAccountIn);

        $in->setIdDirection(Constants::ACCOUNT_DIRECTION_IN);

        $in->setTimestamp(new \DateTime());

        $in->setAmount($model->amount);

        $in->setDescription('Transfer from account ' . $accountOut->getNumber());

        $this->entityManager->persist($in);


        $this->entityManager->flush();


        parent::onSuccess();

        $this->messages = array('Transfer from ' . $accountOut->getNumber() . ' to ' . $accountIn->getNumber() . ' successfully');

    }

}