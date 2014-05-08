<?php
namespace App\AdminBundle\Business\Staff;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\AdminBundle\Exception\MaximumClientsException;
use App\ClientBundle\Entity;
use App\UserBundle\Entity\User;
class CreateHandler extends BaseCreateHandler
{
    public function getDefaultValues()
    {
        return new User();
    }
    public function getOptions()
    {
        return [
            'data_class'        => 'App\UserBundle\Entity\User',
            'validation_groups' => 'create'
        ];
    }

    public function buildForm($builder)
    {
        $builder->add('firstname', 'text', array(
            'attr'     => array(
                'placeholder' => 'FIRST_NAME'
            ),
            'required' => true
        ));
        $builder->add('lastname', 'text', array(
            'attr'     => array(
                'placeholder' => 'LAST_NAME'
            ),
            'required' => true
        ));
        $builder->add('email', 'text', array(
            'attr'     => array(
                'placeholder' => 'EMAIL'
            ),
            'required' => true
        ));
        $builder->add('plainPassword', 'password', array(
            'attr'     => array(
                'placeholder' => 'PASSWORD'
            ),
            'required' => false
        ));
        $builder->add('defaultHourlyRate', 'money', array(
            'attr'     => array(
                'placeholder' => 'HOURLY'
            ),
            'currency' => $this->helperCommon->getConfig()->getBillingCurrency(),
            'required' => false
        ));
        $builder->add('permissionGroup', 'choice', array(
            'attr'     => array(
                'placeholder' => 'PERMISSION_GROUP'
            ),
            'required' => false,
            'choices'  => $this->helperMapping->getMapping('permission_group_list')
        ));
        /*$builder->add('ip', 'text', array(
            'attr'     => array(
                'placeholder' => 'IP_START'
            ),
            'required' => false
        ));
        $builder->add('ipend', 'text', array(
            'attr'     => array(
                'placeholder' => 'IP_END'
            ),
            'required' => false
        ));
        $builder->add('permissions', 'choice', array(
            'attr'     => array(
                'placeholder' => 'PERMISSIONS'
            ),
            'required' => false,
            'expanded' => 'expanded',
            'multiple' => 'multiple',
            'choices'  => $this->helperMapping->getMapping('staff_permission')
        ));*/
        return $builder;
    }
}