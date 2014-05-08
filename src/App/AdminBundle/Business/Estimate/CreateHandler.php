<?php
namespace App\AdminBundle\Business\Estimate;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;
use App\ClientBundle\Entity\ClientInvoice;

class CreateHandler extends BaseCreateHandler
{
    public $newId;

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
        /*$builder->add('subject', 'text', array(
            'attr'     => array(
                'placeholder' => 'Subject'
            ),
            'required' => false
        ));
        $builder->add('issueDate', 'date_picker', array(
            'attr'     => array(
                'placeholder' => 'Issue Date'
            ),
            'required' => false,
            'widget'   => 'single_text'
        ));
        $builder->add('dueDate', 'date_picker', array(
            'attr'     => array(
                'placeholder' => 'Due Date'
            ),
            'required' => false,
            'widget'   => 'single_text'
        ));
        $builder->add('discount', 'percent', array(
            'attr'     => array(
                'placeholder' => 'Discount'
            ),
            'required' => false
        ));
        $builder->add('tax', 'percent', array(
            'attr'     => array(
                'placeholder' => 'Tax'
            ),
            'required' => false
        ));
        
        $builder->add('notes', 'textarea', array(
            'attr'     => array(
                'placeholder' => 'Notes'
            ),
            'required' => false
        ));
        $builder->add('status', 'choice', array(
            'attr'     => array(
                'placeholder' => 'Status'
            ),
            'required' => false,
            'choices'  => $this->helperMapping->getMapping('estimate_status')
        ));
        */
        return $builder;
    }

    public function onSuccess()
    {
        $model = $this->getForm()->getData();

        $estimate = new Entity\ClientEstimate();
        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $estimate);
        $estimate->setSubject('');
        $estimate->setStatus(0);
        $estimate->setInvoiceStatus(ClientInvoice::STATUS_UNPAID);

        $this->entityManager->persist($estimate);
        $this->entityManager->flush();

        Utils::updateEstimatePrefix($this->container, $estimate->getId());

        $this->newId = $estimate->getId();

        parent::onSuccess();
    }
}