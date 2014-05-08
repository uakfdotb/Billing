<?php
namespace App\AdminBundle\Business\Base;

abstract class BaseEditHandler extends BaseHandler
{
    protected $builder = null;
    protected $entity;
    protected $helperForm;
    protected $helperDoctrine;
    protected $helperCommon;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->helperForm = $container->get('app_admin.helper.form');
        $this->helperDoctrine = $container->get('app_admin.helper.doctrine');
        $this->helperCommon = $container->get('app_admin.helper.common');
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    abstract function loadEntity();
    abstract function getModelFromEntity($entity);
    abstract function buildForm($builder);

    public function onSuccess()
    {
        $this->messages[] = 'THE_RECORD_HAS_BEEN_UPDATED_SUCCESSFULLY';
        //$this->form = $this->builder->getForm();
        $em = $this->container->get('doctrine')->getEntityManager();
        if (is_array($this->getEntity())) {
            foreach ($this->getEntity() as $e) {
                $em->persist($e);
            }
        } else {
            $em->persist($this->getEntity());
        }
        $em->flush();
    }


    public function execute()
    {
        $request = $this->container->get('request');

        $entity = $this->loadEntity();
        $this->setEntity($entity);
        $formModel = $this->getModelFromEntity($entity);

        $this->builder = $this->container->get('form.factory')->createBuilder('form', $formModel);
        $this->buildForm($this->builder);

        if ($request->getMethod() == 'POST' && $this->isAccept()) {
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

