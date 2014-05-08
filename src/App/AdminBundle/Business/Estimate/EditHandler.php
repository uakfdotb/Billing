<?php
namespace App\AdminBundle\Business\Estimate;

use App\AdminBundle\Business\Base\BaseEditHandler;
use App\ClientBundle\Entity;

class EditHandler extends BaseEditHandler
{
    public function loadEntity()
    {
        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:ClientEstimate');
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
        $builder->add('subject', 'text', array(
            'attr'     => array(
                'placeholder' => 'SUBJECT'
            ),
            'required' => false
        ));
        $builder->add('issueDate', 'date_picker', array(
            'attr'     => array(
                'placeholder' => 'ISSUE_DATE'
            ),
            'required' => false,
            'widget'   => 'single_text'
        ));
        $builder->add('dueDate', 'date_picker', array(
            'attr'     => array(
                'placeholder' => 'VALID_UNTIL'
            ),
            'required' => false,
            'widget'   => 'single_text'
        ));
        $builder->add('discount', 'percent', array(
            'attr'     => array(
                'placeholder' => 'DISCOUNT'
            ),
            'required' => false
        ));
        $builder->add('tax', 'choice', array(
            'attr'     => array(
                'placeholder' => 'TAX GROUP'
            ),
            'required' => false,
            'choices'  => $this->helperMapping->getMapping('tax_list')
        ));

        $builder->add('notes', 'textarea', array(
            'attr'     => array(
                'placeholder' => 'NOTES'
            ),
            'required' => false
        ));

        $builder->add('status', 'choice', array(
            'attr'     => array(
                'placeholder' => 'STATUS'
            ),
            'required' => false,
            'choices'  => $this->helperMapping->getMapping('estimate_status')
        ));


        return $builder;
    }

    public function onSuccess()
    {
        $model  = $this->getForm()->getData();
        $entity = $this->getEntity();

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $entity);
        if (empty($model->status)) {
            $entity->setStatus(0);
        }

        Utils::updateEstimateStatus($this->container, $entity->getId());

        parent::onSuccess();
    }
}