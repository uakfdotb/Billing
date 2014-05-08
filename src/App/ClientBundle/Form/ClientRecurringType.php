<?php

namespace App\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientRecurringType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idClient')
            ->add('subject')
            ->add('idSchedule')
            ->add('discount')
            ->add('tax')
            ->add('nextDue')
            ->add('notes')
            ->add('isInvoiced')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\ClientBundle\Entity\ClientRecurring'
        ));
    }

    public function getName()
    {
        return 'app_clientbundle_clientrecurringtype';
    }
}
