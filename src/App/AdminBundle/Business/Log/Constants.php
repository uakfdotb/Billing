<?php

namespace App\AdminBundle\Business\Log;

class Constants
{
    const LOG_TYPE_LOGIN_SUCCESS              = 1;
    const LOG_TYPE_LOGIN_FAILED               = 2;
    const LOG_TYPE_IPN_SUCCESS                = 3;
    const LOG_TYPE_IPN_FAILED                 = 4;
    const LOG_TYPE_CPANEL_CREATE_USER_FAILED  = 5;
    const LOG_TYPE_CPANEL_CREATE_USER_RESULT  = 6;
    const LOG_TYPE_SOLUSVM_CREATE_USER_FAILED = 7;
    const LOG_TYPE_SOLUSVM_CREATE_USER_RESULT = 8;

    public static function getLogTypes()
    {
        return array(
            self::LOG_TYPE_LOGIN_SUCCESS              => 'Login (success)',
            self::LOG_TYPE_LOGIN_FAILED               => 'Login (failed)',
            self::LOG_TYPE_IPN_SUCCESS                => 'IPN (success)',
            self::LOG_TYPE_IPN_FAILED                 => 'IPN (failed)',
            self::LOG_TYPE_CPANEL_CREATE_USER_FAILED  => 'Cpanel - Create User (failed)',
            self::LOG_TYPE_CPANEL_CREATE_USER_RESULT  => 'Cpanel - Create User (result)',
            self::LOG_TYPE_SOLUSVM_CREATE_USER_FAILED => 'Solus VM - Create User (failed)',
            self::LOG_TYPE_SOLUSVM_CREATE_USER_RESULT => 'Solus VM - Create User (result)',
        );
    }
}

