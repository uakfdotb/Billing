<?php

namespace App\AdminBundle\Business\Recurring;


use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\AdminBundle\Business\Recurring\Constants;
use App\ClientBundle\Entity;


class CreateHandler extends BaseCreateHandler
{

    public $newId = 0;

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
            'required' => true,
            'choices'  => $this->helperMapping->getMapping('client_list')
        ));

        return $builder;
    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();
        $config = $this->entityManager->getRepository('AppClientBundle:Config')->find(1);

        $recurring = new Entity\ClientRecurring();

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $recurring);

        $recurring->setDiscount(0);
        $recurring->setTax(0);
        $recurring->setNextDue(new \DateTime("now"));
        $recurring->setIdSchedule(Constants::SCHEDULE_MONTHLY);
        $recurring->setNotes($config->getInvoiceNotes());

        $this->entityManager->persist($recurring);

        $this->entityManager->flush();


        $this->newId = $recurring->getId();


        parent::onSuccess();

    }

}