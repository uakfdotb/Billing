<?php

namespace App\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientProjectTrackingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idProject')
            ->add('start')
            ->add('stop')
            ->add('staff')
            ->add('hourly')
            ->add('invoiced')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\ClientBundle\Entity\ClientProjectTracking'
        ));
    }

    public function getName()
    {
        return 'app_clientbundle_clientprojecttrackingtype';
    }
}
