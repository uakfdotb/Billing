<?php

namespace App\AdminBundle\Business\ClientLog;

class Constants
{
    const LOG_TYPE_LOGIN_SUCCESS  = 10;
    const LOG_TYPE_LOGIN_FAILED   = 11;
    const LOG_TYPE_INVOICE_LIST   = 20;
    const LOG_TYPE_INVOICE_VIEW   = 21;
    const LOG_TYPE_ESTIMATE_VIEW  = 22;
    const LOG_TYPE_TICKET_LIST    = 30;
    const LOG_TYPE_TICKET_CREATE  = 31;
    const LOG_TYPE_TICKET_COMMENT = 32;
    const LOG_TYPE_PROJECT_LIST   = 40;
    const LOG_TYPE_PROJECT_VIEW   = 41;
    const LOG_TYPE_ORDER_LIST     = 50;
    const LOG_TYPE_PROFILE_VIEW   = 60;
    const LOG_TYPE_PROFILE_EDIT   = 61;
    const LOG_TYPE_CONTACT_LIST   = 70;
    const LOG_TYPE_CONTACT_CREATE = 71;
    const LOG_TYPE_CONTACT_EDIT   = 72;
    const LOG_TYPE_CONTACT_DELETE = 73;
    const LOG_TYPE_GATEWAY_LIST   = 80;
    const LOG_TYPE_GATEWAY_NEW    = 81;
    const LOG_TYPE_GATEWAY_CREATE = 82;
    const LOG_TYPE_GATEWAY_EDIT   = 83;
    const LOG_TYPE_GATEWAY_UPDATE = 84;
    const LOG_TYPE_GATEWAY_DELETE = 85;

    public static function getLogTypes()
    {
        return array(
            self::LOG_TYPE_LOGIN_SUCCESS  => 'Login',
            // invoice
            self::LOG_TYPE_INVOICE_LIST   => 'View invoice list',
            self::LOG_TYPE_INVOICE_VIEW   => 'View an invoice',
            // estimate
            self::LOG_TYPE_ESTIMATE_VIEW  => 'View an estimate',
            // ticket
            self::LOG_TYPE_TICKET_LIST    => 'View ticket list',
            self::LOG_TYPE_TICKET_CREATE  => 'Create new ticket',
            self::LOG_TYPE_TICKET_COMMENT => 'Comment on a ticket',
            // project
            self::LOG_TYPE_PROJECT_LIST   => 'View project list',
            self::LOG_TYPE_PROJECT_VIEW   => 'View a project',
            // order
            self::LOG_TYPE_ORDER_LIST     => 'View order list',
            // profile
            //self::LOG_TYPE_PROFILE_VIEW    => 'View profile',
            self::LOG_TYPE_PROFILE_EDIT   => 'Edit profile',
            // contact
            self::LOG_TYPE_CONTACT_LIST   => 'View contact list',
            self::LOG_TYPE_CONTACT_CREATE => 'Create new contact',
            self::LOG_TYPE_CONTACT_EDIT   => 'Edit an contact',
            self::LOG_TYPE_CONTACT_DELETE => 'Delete an contact',
            // gateway
            self::LOG_TYPE_GATEWAY_LIST   => 'Gateway List',
            self::LOG_TYPE_GATEWAY_NEW    => 'New gateway',
            self::LOG_TYPE_GATEWAY_CREATE => 'Create gateway',
            self::LOG_TYPE_GATEWAY_EDIT   => 'Edit gateway',
            self::LOG_TYPE_GATEWAY_UPDATE => 'Update gateway',
            self::LOG_TYPE_GATEWAY_DELETE => 'Delete gateway'
        );
    }
}

