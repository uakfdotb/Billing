<?php

namespace App\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SupplierPurchaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idSupplier')
            ->add('name')
            ->add('purchaseDate')
            ->add('idAccountTransaction')
            ->add('amount')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\ClientBundle\Entity\SupplierPurchase'
        ));
    }

    public function getName()
    {
        return 'app_clientbundle_supplierpurchasetype';
    }
}
