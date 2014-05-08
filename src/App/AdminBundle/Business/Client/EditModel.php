<?php
namespace App\AdminBundle\Business\Client;

use Symfony\Component\Validator\ExecutionContext;

class EditModel
{
    public $container;
    public $entityId = 0;

    public $firstname;
    public $lastname;
    public $company;
    public $address1;
    public $address2;
    public $city;
    public $state;
    public $postcode;
    public $idCountry;
    public $phone;
    public $email;
    public $plainPassword;
    public $status;
    public $vatNumber;

    public function isValid(ExecutionContext $context)
    {
        $helperDoctrine = $this->container->get('app_admin.helper.doctrine');
        if ($helperDoctrine->isNotExist('AppUserBundle:User', 'email', $this->email, $this->entityId) === false) {
            $context->addViolationAtSubPath('email', "Email '" . $this->email . "' already exists");
        }
    }
}