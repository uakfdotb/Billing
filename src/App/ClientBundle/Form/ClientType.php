<?php

namespace App\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientType extends AbstractType
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
            ->add('status')
            ->add('vatNumber')
            ->add('addedDate')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\ClientBundle\Entity\Client'
        ));
    }

    public function getName()
    {
        return 'app_clientbundle_clienttype';
    }
}
