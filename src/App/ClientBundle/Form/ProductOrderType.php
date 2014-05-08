<?php

namespace App\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idProduct')
            ->add('idClient')
            ->add('timestamp')
            ->add('idOrderPaymentTerm')
            ->add('status')
            ->add('orderNumber')
            ->add('ipAddress')
            ->add('idEstimate')
            ->add('maxmindData')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\ClientBundle\Entity\ProductOrder'
        ));
    }

    public function getName()
    {
        return 'app_clientbundle_productordertype';
    }
}
