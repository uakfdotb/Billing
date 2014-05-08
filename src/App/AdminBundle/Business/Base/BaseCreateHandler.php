<?php

namespace App\AdminBundle\Business\Base;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;

abstract class BaseCreateHandler extends BaseHandler
{
    /** @var FormBuilder $builder */
    protected $builder;

    /** @var Container */
    protected $container;

    abstract function getDefaultValues();

    abstract function buildForm($builder);

    public function onSuccess()
    {
        $this->messages[] = 'THE_RECORD_HAS_BEEN_CREATED_SUCCESSFULLY';
        $this->builder->setData($this->getDefaultValues());

        $this->form = $this->builder->getForm();
    }

    public function execute()
    {
        $request = $this->container->get('request');
        $defaultFormModel = $this->getDefaultValues();

        /** @var FormFactory $formFactory */
        $formFactory = $this->container->get('form.factory');

        $this->builder = $formFactory->createBuilder('form', $defaultFormModel, $this->getOptions());
        $this->buildForm($this->builder);

        if ($request->getMethod() == 'POST' && $this->isAccept()) {
            $form = $this->builder->getForm();
            $form->bind($request);
            $this->form = $form;
            if ($form->isValid()) {
                $this->onSuccess();
            } else {
                $this->onFailure();
            }
        } else {
            $this->form = $this->builder->getForm();
        }
    }

    public function getOptions()
    {
        return [];
    }
}