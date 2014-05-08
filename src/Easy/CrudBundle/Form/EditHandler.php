<?php
namespace Easy\CrudBundle\Form;

abstract class EditHandler extends BaseHandler
{
    protected $builder = null;
    protected $entity;

    abstract function loadEntity($request);

    abstract function convertToFormModel($entity);

    abstract function buildForm($builder);

    public function getEntityManager()
    {
        return '';
    }

    public function onSuccess()
    {
        $this->messages[] = 'The record has been updated successfully';
        //$this->form = $this->builder->getForm();

        $em = $this->controller->getDoctrine()->getEntityManager($this->getEntityManager());
        if (is_array($this->entity)) {
            foreach ($this->entity as $e) {
                $em->persist($e);
            }
        } else {
            $em->persist($this->entity);
        }
        $em->flush();
    }

    public function execute()
    {
        $request = $this->controller->getRequest();

        $this->entity = $this->loadEntity($request);
        $formModel    = $this->convertToFormModel($this->entity);

        $this->builder = $this->controller->createFormBuilder($formModel);
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
