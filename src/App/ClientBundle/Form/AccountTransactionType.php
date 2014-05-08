<?php

namespace App\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AccountTransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idAccount')
            ->add('idDirection')
            ->add('timestamp')
            ->add('amount')
            ->add('description')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\ClientBundle\Entity\AccountTransaction'
        ));
    }

    public function getName()
    {
        return 'app_clientbundle_accounttransactiontype';
    }
}
