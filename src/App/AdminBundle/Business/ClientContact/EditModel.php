<?php

namespace App\AdminBundle\Business\ClientContact;


use Symfony\Component\Validator\ExecutionContext;


class EditModel
{

    public $container;

    public $entityId = 0;


    public $firstname;

    public $lastname;

    public $email;

    public $password;

    public $permissions;

    public function isValid(ExecutionContext $context)
    {

        $helperDoctrine = $this->container->get('app_admin.helper.doctrine');

        if ($helperDoctrine->isNotExist('AppUserBundle:User', 'email', $this->email) === false ||

            $helperDoctrine->isNotExist('AppClientBundle:ClientContact', 'email', $this->email, $this->entityId) === false
        ) {

            $context->addViolationAtSubPath('email', "Email '" . $this->email . "' already exists");

        }

    }

}