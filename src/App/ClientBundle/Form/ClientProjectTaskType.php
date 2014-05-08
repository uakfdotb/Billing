<?php

namespace App\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientProjectTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idProject')
            ->add('idWorkType')
            ->add('subject')
            ->add('timestamp')
            ->add('quantity')
            ->add('unit')
            ->add('unitPrice')
            ->add('isBillable')
            ->add('invoiced')
            ->add('status')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\ClientBundle\Entity\ClientProjectTask'
        ));
    }

    public function getName()
    {
        return 'app_clientbundle_clientprojecttasktype';
    }
}
