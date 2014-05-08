<?php

namespace App\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientEstimateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idClient')
            ->add('subject')
            ->add('issueDate')
            ->add('dueDate')
            ->add('hash')
            ->add('discount')
            ->add('tax')
            ->add('notes')
            ->add('status')
            ->add('invoiceStatus')
            ->add('totalAmount')
            ->add('totalPayment')
            ->add('number')
            ->add('invoiced')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\ClientBundle\Entity\ClientEstimate'
        ));
    }

    public function getName()
    {
        return 'app_clientbundle_clientestimatetype';
    }
}
