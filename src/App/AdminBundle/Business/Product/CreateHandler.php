<?php

namespace App\AdminBundle\Business\Product;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class CreateHandler extends BaseCreateHandler
{
    public $customFieldManager;

    public function getDefaultValues()
    {
        $this->customFieldManager = $this->container->get('app_admin.business.product.custom_field.manager');
        $model                    = new CreateModel();

        return $model;
    }

    public function buildForm($builder)
    {
        $builder->add('name', 'text', array(
            'label' => 'NAME',
            'required' => true
        ));
        $builder->add('idType', 'choice', array(
            'attr'     => array(
                'placeholder' => 'TYPE'
            ),
            'label' => 'TYPE',
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
            'label' => 'PRODUCT_GROUP',
            'choices'  => $this->helperMapping->getMapping('product_group_list'),
            'required' => false
        ));
        $builder->add('idEmail', 'choice', array(
            'attr'     => array(
                'placeholder' => 'WELCOME_EMAIL'
            ),
            'label' => 'WELCOME_EMAIL',
            'choices'  => $this->helperMapping->getMapping('email_list'),
            'required' => false
        ));
        $builder->add('stock', 'integer', array(
            'label' => 'STOCK',
            'required' => false
        ));
        $builder->add('isAvailable', 'checkbox', array(
            'attr'     => array(
                'placeholder' => 'AVAILABLE_IN_ORDER_FORM'
            ),
            'label' => 'AVAILABLE_IN_ORDER_FORM',
            'required' => false
        ));
        $builder->add('idPaymentType', 'choice', array(
            'attr'     => array(
                'placeholder' => 'PAYMENT_TYPE'
            ),
            'label' => 'PAYMENT_TYPE',
            'required' => true,
            'choices'  => $this->helperMapping->getMapping('product_payment_type')
        ));
        $builder->add('isRedirectUnpaidInvoice', 'checkbox', array(
            'attr'     => array(
                'placeholder' => 'IS_REDIRECT_UNPAID_INVOICE'
            ),
            'label' => 'IS_REDIRECT_UNPAID_INVOICE',
            'required' => false
        ));

        // Setup fee
        $builder->add('setupFeeMonthly', 'number', array(
            'attr'     => array(
                'class' => 'input-mini',
                'placeholder' => '0.00'
            ),
            'required' => false
        ));
        $builder->add('setupFeeQuarterly', 'number', array(
            'attr'     => array(
                'class' => 'input-mini',
                'placeholder' => '0.00'
            ),
            'required' => false
        ));
        $builder->add('setupFeeSemiAnnually', 'number', array(
            'attr'     => array(
                'class' => 'input-mini',
                'placeholder' => '0.00'
            ),
            'required' => false
        ));
        $builder->add('setupFeeAnnually', 'number', array(
            'attr'     => array(
                'class' => 'input-mini',
                'placeholder' => '0.00'
            ),
            'required' => false
        ));
        $builder->add('setupFeeBiennially', 'number', array(
            'attr'     => array(
                'class' => 'input-mini',
                'placeholder' => '0.00'
            ),
            'required' => false
        ));
        $builder->add('setupFeeTriennially', 'number', array(
            'attr'     => array(
                'class' => 'input-mini',
                'placeholder' => '0.00'
            ),
            'required' => false
        ));

        // Price
        $builder->add('priceMonthly', 'number', array(
            'attr'     => array(
                'class' => 'input-mini',
                'placeholder' => '0.00'
            ),
            'required' => false
        ));
        $builder->add('priceQuarterly', 'number', array(
            'attr'     => array(
                'class' => 'input-mini',
                'placeholder' => '0.00'
            ),
            'required' => false
        ));
        $builder->add('priceSemiAnnually', 'number', array(
            'attr'     => array(
                'class' => 'input-mini',
                'placeholder' => '0.00'
            ),
            'required' => false
        ));
        $builder->add('priceAnnually', 'number', array(
            'attr'     => array(
                'class' => 'input-mini',
                'placeholder' => '0.00'
            ),
            'required' => false
        ));
        $builder->add('priceBiennially', 'number', array(
            'attr'     => array(
                'class' => 'input-mini',
                'placeholder' => '0.00'
            ),
            'required' => false
        ));
        $builder->add('priceTriennially', 'number', array(
            'attr'     => array(
                'class' => 'input-mini',
                'placeholder' => '0.00'
            ),
            'required' => false
        ));

        $builder->add('priceTriennially', 'number', array(
            'attr'     => array(
                'class' => 'input-mini',
                'placeholder' => '0.00'
            ),
            'required' => false
        ));

        // Automation groups
        $builder->add('triggerCreate', 'choice', array(
            'attr'     => array(
                'placeholder' => 'CREATE_ACCOUNT'
            ),
            'label' => 'CREATE_ACCOUNT',
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
        $model->features = $this->removeEmpty($model->features);

        $product = new Entity\Product();
        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $product);

        // Deal with custom fields
        $formData = $this->container->get('request')->request->all()['form'];
        $this->customFieldManager->setModule($model->idType);
        $product->setModuleSettings($this->customFieldManager->saveData($formData));

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        parent::onSuccess();
    }
}