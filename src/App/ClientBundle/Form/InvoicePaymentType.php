<?php

namespace App\InvoicePaymentsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InvoicePaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('credit_card', 'form', [])
            ->add('first_name', 'text', [])
            ->add('last_name', 'text', [])
            ->add('number', 'text', [])
            ->add('month', 'text', [])
            ->add('year', 'text', [])
            ->add('verification_value', 'text', [])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([]);
    }

    public function getName()
    {
        return 'app_invoicepaymentsbundle_invoicepaymenttype';
    }
}
