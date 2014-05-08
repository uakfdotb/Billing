<?php

namespace App\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SupplierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('company')
            ->add('address1')
            ->add('address2')
            ->add('city')
            ->add('state')
            ->add('postcode')
            ->add('idCountry')
            ->add('phone')
            ->add('email')
            ->add('password')
            ->add('status')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\ClientBundle\Entity\Supplier'
        ));
    }

    public function getName()
    {
        return 'app_clientbundle_suppliertype';
    }
}
