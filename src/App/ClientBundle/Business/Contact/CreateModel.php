<?php
namespace App\ClientBundle\Business\Contact;

use Symfony\Component\Validator\ExecutionContext;

class CreateModel
{
    public $container;

    public $firstname;
    public $lastname;
    public $email;
    //public $password;
    //public $permissions;

    public function isValid(ExecutionContext $context)
    {
        $helperDoctrine = $this->container->get('app_admin.helper.doctrine');
        if ($helperDoctrine->isNotExist('AppUserBundle:User', 'email', $this->email) === false ||
            $helperDoctrine->isNotExist('AppClientBundle:ClientContact', 'email', $this->email) === false
        ) {
            $context->addViolationAtSubPath('email', "Email '" . $this->email . "' is already existed");
        }
    }
}