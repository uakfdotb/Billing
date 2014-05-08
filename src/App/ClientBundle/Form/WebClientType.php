<?php

namespace App\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\True;

class WebClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('email')
            ->add('password')
            ->add('firstname')
            ->add('lastname')
            ->add('company')
            ->add('address1')
            ->add('address2')
            ->add('city')
            ->add('state')
            ->add('postcode')
            ->add('idCountry')
            ->add('terms', 'checkbox', [
                'mapped'      => false,
                'constraints' => new True(),
            ])
            ->add('token', 'text', [
                'mapped' => false
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\ClientBundle\Entity\Client',
            'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return 'web_clientbundle_webclienttype';
    }

    public function getErrors($form)
    {
        $errors = [];

        foreach ($form->getChildren() as $key => $child)
        {
            $childErrors = $child->getErrors();

            if (!empty($childErrors))
            {
                $errors[$key] = $childErrors[0]->getMessage();
            }
        }

        return $errors;
    }
}
