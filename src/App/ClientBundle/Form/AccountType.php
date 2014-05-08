<?php

namespace App\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('number')
            ->add('sortCode')
            ->add('idAccountType')
            ->add('street1')
            ->add('street2')
            ->add('idCountry')
            ->add('postcode')
            ->add('county')
            ->add('town')
            ->add('telephone')
            ->add('email')
            ->add('balance')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\ClientBundle\Entity\Account'
        ));
    }

    public function getName()
    {
        return 'app_clientbundle_accounttype';
    }
}
