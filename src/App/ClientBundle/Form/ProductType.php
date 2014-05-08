<?php

namespace App\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('idProductGroup')
            ->add('idEmail')
            ->add('stock')
            ->add('idPaymentType')
            ->add('setupFeeMonthly')
            ->add('setupFeeQuarterly')
            ->add('setupFeeSemiAnnually')
            ->add('setupFeeAnnually')
            ->add('setupFeeBiennially')
            ->add('setupFeeTriennially')
            ->add('priceMonthly')
            ->add('priceQuarterly')
            ->add('priceSemiAnnually')
            ->add('priceAnnually')
            ->add('priceBiennially')
            ->add('priceTriennially')
            ->add('isAvailable')
            ->add('isGenerateInvoice')
            ->add('idType')
            ->add('customData')
            ->add('isRedirectUnpaidInvoice')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\ClientBundle\Entity\Product'
        ));
    }

    public function getName()
    {
        return 'app_clientbundle_producttype';
    }
}
