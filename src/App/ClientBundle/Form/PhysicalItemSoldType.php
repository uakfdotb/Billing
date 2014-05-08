<?php

namespace App\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PhysicalItemSoldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idPhysicalItem')
            ->add('idClient')
            ->add('dateOut')
            ->add('sellPrice')
            ->add('quantity')
            ->add('serialNumber')
            ->add('invoiced')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\ClientBundle\Entity\PhysicalItemSold'
        ));
    }

    public function getName()
    {
        return 'app_clientbundle_physicalitemsoldtype';
    }
}
