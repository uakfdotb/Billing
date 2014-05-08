<?php
namespace Easy\CrudBundle\Form;

abstract class FilterHandler extends BaseHandler
{
    protected $sessionId;

    public function __construct($controller, $sessionId)
    {
        parent::__construct($controller);
        $this->sessionId = $sessionId;
    }

    abstract public function getDefaultFormModel();

    abstract function buildForm($builder);

    protected function setFilterData()
    {
        $filterModel = $this->getForm()->getData();
        $session     = $this->controller->getRequest()->getSession();
        if (!is_object($filterModel)) {
            $filterModel = $this->getDefaultFormModel();
        }
        $session->set($this->sessionId, $filterModel);
    }

    public function onSuccess()
    {
        $this->setFilterData();
    }

    public function getCurrentFilter()
    {
        $session = $this->controller->getRequest()->getSession();

        if ($session->has($this->sessionId)) {
            $filterModel = $session->get($this->sessionId);
            if (!is_object($filterModel)) {
                $filterModel = $this->getDefaultFormModel();
            }
            return $filterModel;
        } else {
            $filterModel = $this->getDefaultFormModel();
            $session->set($this->sessionId, $filterModel);
            return $filterModel;
        }
    }

    public function execute()
    {
        $request = $this->controller->getRequest();

        $defaultFormModel = $this->getCurrentFilter();
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
