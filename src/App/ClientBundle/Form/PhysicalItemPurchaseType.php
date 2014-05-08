<?php

namespace App\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PhysicalItemPurchaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idPhysicalItem')
            ->add('idSupplier')
            ->add('dateIn')
            ->add('purchasePrice')
            ->add('quantity')
            ->add('serialNumber')
            ->add('isPurchased')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\ClientBundle\Entity\PhysicalItemPurchase'
        ));
    }

    public function getName()
    {
        return 'app_clientbundle_physicalitempurchasetype';
    }
}
