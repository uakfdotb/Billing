<?php

namespace App\AdminBundle\Business\Product;

use App\AdminBundle\Business\Base\BaseEditHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class EditHandler extends BaseEditHandler
{
    public $customFieldManager;

    public function loadEntity()
    {
        $this->customFieldManager = $this->container->get('app_admin.business.product.custom_field.manager');
        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:Product');
    }

    public function getModelFromEntity($entity)
    {
        $model = new EditModel();
        $this->helperCommon->copyEntityToModel($this->entity, $model);

        return $model;
    }

    public function buildForm($builder)
    {
        $builder->add('name', 'text', array(
            'attr'     => array(
                'placeholder' => 'NAME'
            ),
            'required' => true
        ));
        $builder->add('idType', 'choice', array(
            'attr'     => array(
                'placeholder' => 'TYPE'
            ),
            'choices'  => $this->helperMapping->getMapping('product_type_list'),
            'required' => false
        ));
        $builder->add('serverGroup', 'choice', array(
            'attr'     => array(
                'placeholder' => 'SERVER_GROUP'
            ),
            'label' => 'SERVER_GROUP',
            'required' => false,
            'choices' => $this->helperMapping->getMapping('server_group_list'),
        ));
        $builder->add('color', 'text', array(
            'attr'     => array(
                'placeholder' => 'COLOR'
            ),
            'required' => false
        ));
        $builder->add('idProductGroup', 'choice', array(
            'attr'     => array(
                'placeholder' => 'PRODUCT_GROUP'
            ),
            'choices'  => $this->helperMapping->getMapping('product_group_list'),
            'required' => false
        ));
        $builder->add('idEmail', 'choice', array(
            'attr'     => array(
                'placeholder' => 'WELCOME_EMAIL'
            ),
            'choices'  => $this->helperMapping->getMapping('email_list'),
            'required' => false
        ));
        $builder->add('stock', 'integer', array(
            'attr'     => array(
                'placeholder' => 'STOCK'
            ),
            'required' => false
        ));
        $builder->add('isAvailable', 'checkbox', array(
            'attr'     => array(
                'placeholder' => 'AVAILABLE_IN_ORDER_FORM'
            ),
            'required' => false
        ));
        $builder->add('idPaymentType', 'choice', array(
            'attr'     => array(
                'placeholder' => 'PAYMENT_TYPE'
            ),
            'required' => true,
            'choices'  => $this->helperMapping->getMapping('product_payment_type')
        ));
        $builder->add('isRedirectUnpaidInvoice', 'checkbox', array(
            'attr'     => array(
                'placeholder' => 'IS_REDIRECT_UNPAID_INVOICE'
            ),
            'required' => false
        ));

        // Setup fee
        $builder->add('setupFeeMonthly', 'number', array(
            'attr'     => array(
                'class' => 'input-mini'
            ),
            'required' => false
        ));
        $builder->add('setupFeeQuarterly', 'number', array(
            'attr'     => array(
                'class' => 'input-mini'
            ),
            'required' => false
        ));
        $builder->add('setupFeeSemiAnnually', 'number', array(
            'attr'     => array(
                'class' => 'input-mini'
            ),
            'required' => false
        ));
        $builder->add('setupFeeAnnually', 'number', array(
            'attr'     => array(
                'class' => 'input-mini'
            ),
            'required' => false
        ));
        $builder->add('setupFeeBiennially', 'number', array(
            'attr'     => array(
                'class' => 'input-mini'
            ),
            'required' => false
        ));
        $builder->add('setupFeeTriennially', 'number', array(
            'attr'     => array(
                'class' => 'input-mini'
            ),
            'required' => false
        ));

        // Price
        $builder->add('priceMonthly', 'number', array(
            'attr'     => array(
                'class' => 'input-mini'
            ),
            'required' => false
        ));
        $builder->add('priceQuarterly', 'number', array(
            'attr'     => array(
                'class' => 'input-mini'
            ),
            'required' => false
        ));
        $builder->add('priceSemiAnnually', 'number', array(
            'attr'     => array(
                'class' => 'input-mini'
            ),
            'required' => false
        ));
        $builder->add('priceAnnually', 'number', array(
            'attr'     => array(
                'class' => 'input-mini'
            ),
            'required' => false
        ));
        $builder->add('priceBiennially', 'number', array(
            'attr'     => array(
                'class' => 'input-mini'
            ),
            'required' => false
        ));
        $builder->add('priceTriennially', 'number', array(
            'attr'     => array(
                'class' => 'input-mini'
            ),
            'required' => false
        ));

        // Automation groups
        $builder->add('triggerCreate', 'choice', array(
            'attr'     => array(
                'placeholder' => 'CREATE_ACCOUNT'
            ),
            'required' => false,
            'choices'  => $this->helperMapping->getMapping('order_create_account')
        ));

        // Features
        $builder->add('features', 'collection', array(
            'type'         => 'text',
            'required'     => false,
            'allow_add'    => true,
            'allow_delete' => true
        ));

        $this->customFieldManager->buildForm($builder);

        return $builder;
    }


    public function onSuccess()
    {
        $model = $this->getForm()->getData();
        $entity = $this->getEntity();
        $model->features = $this->removeEmpty($model->features);

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $entity);

        // Deal with custom fields
        $formData = $this->container->get('request')->request->all()['form'];
        $this->customFieldManager->setModule($model->idType);
        $entity->setModuleSettings($this->customFieldManager->saveData($formData));

        parent::onSuccess();
    }
}