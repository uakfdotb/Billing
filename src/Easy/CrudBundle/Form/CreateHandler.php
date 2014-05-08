<?php
namespace Easy\CrudBundle\Form;

abstract class CreateHandler extends BaseHandler
{
    protected $builder = null;

    abstract function getDefaultFormModel();

    abstract function buildForm($builder);

    public function onSuccess()
    {
        $this->messages[] = 'The record has been created successfully';
        $this->builder->setData($this->getDefaultFormModel());
        $this->form = $this->builder->getForm();
    }

    public function execute()
    {
        $request = $this->controller->getRequest();

        $defaultFormModel = $this->getDefaultFormModel();
        $this->builder    = $this->controller->createFormBuilder($defaultFormModel);
        $this->buildForm($this->builder);

        if ($request->getMethod() == 'POST') {
            $form = $this->builder->getForm();
            $form->bindRequest($request);
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
}

