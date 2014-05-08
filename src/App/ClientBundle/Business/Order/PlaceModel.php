<?php

namespace App\ClientBundle\Business\Order;
use Symfony\Component\Validator\ExecutionContext;
use App\AdminBundle\Business as AdminBusiness;

class PlaceModel
{
    public $container;
    public $pid;
    public $placeNew;
    public $idOrderPaymentTerm;

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
    public $password;
    public $status;


    public $idPackage;
    public $domain;
    public $accountUsername;
    public $accountPassword;

    public function isValid(ExecutionContext $context)
    {
        $helperDoctrine = $this->container->get('app_admin.helper.doctrine');
        if ($helperDoctrine->isNotExist('AppUserBundle:User', 'email', $this->email) === false) {
            $context->addViolationAtSubPath('email', "Email '" . $this->email . "' is already existed");
        }

        // Validate cpanel
        $doctrine = $this->container->get('doctrine');
        $product  = $doctrine->getRepository('AppClientBundle:Product')->findOneById($this->pid);
        if ($product) {
            if ($product) {
                if ($product->getIdType() == AdminBusiness\Product\Constants::PRODUCT_TYPE_CPANEL) {
                    if (empty($this->domain)) {
                        $context->addViolationAtSubPath('domain', 'Domain must be be empty');
                    }
                    if (empty($this->accountUsername) || strlen($this->accountUsername) > 8) {
                        $context->addViolationAtSubPath('accountUsername', 'Account username must be from 1 to 8 characters');
                    } else if ($this->accountUsername == 'root') {
                        $context->addViolationAtSubPath('accountUsername', 'Account username must be \'root\'');
                    } else {
                        $cpanel = $this->container->get('app_admin.helper.cpanel');
                        $msg    = $cpanel->verifyUsername($this->accountUsername);
                        if (!empty($msg)) {
                            $context->addViolationAtSubPath('accountUsername', $msg);
                        }
                    }
                    if (empty($this->accountPassword)) {
                        $context->addViolationAtSubPath('accountPassword', 'Account password must not be empty');
                    }
                } else if ($product->getIdType() == AdminBusiness\Product\Constants::PRODUCT_TYPE_SOLUSVM) {
                    if (empty($this->domain)) {
                        $context->addViolationAtSubPath('domain', 'Domain must be be empty');
                    }
                    if (empty($this->accountUsername) || strlen($this->accountUsername) > 8) {
                        $context->addViolationAtSubPath('accountUsername', 'Account username must be from 1 to 8 characters');
                    } else if ($this->accountUsername == 'root') {
                        $context->addViolationAtSubPath('accountUsername', 'Account username must be \'root\'');
                    } else {
                        $solusvm = $this->container->get('app_admin.helper.solusvm');
                        $msg     = $solusvm->verifyUsername($this->accountUsername);
                        if (!empty($msg)) {
                            $context->addViolationAtSubPath('accountUsername', $msg);
                        }
                    }
                    if (empty($this->accountPassword)) {
                        $context->addViolationAtSubPath('accountPassword', 'Account password must not be empty');
                    }
                }
            }
        }
    }
}

