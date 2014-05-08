<?php

namespace App\AdminBundle\Business\ClientContact;


class Constants
{
    const PAGE_INVOICE = 10;
    const PAGE_TICKET  = 20;
    const PAGE_PROJECT = 30;
    const PAGE_ORDER   = 40;
    const PAGE_PROFILE = 50;
    const PAGE_CONTACT = 60;
    const PAGE_GATEWAY = 70;

    public static function getPermissions()
    {
        return array(
            self::PAGE_INVOICE => 'Invoice',
            self::PAGE_TICKET  => 'Ticket',
            self::PAGE_PROJECT => 'Project',
            self::PAGE_ORDER   => 'Order',
            self::PAGE_PROFILE => 'Profile',
            self::PAGE_CONTACT => 'Contact',
            self::PAGE_GATEWAY => 'Gateways',
        );
    }


    public static function getPermissionCodes()
    {
        return array(
            self::PAGE_INVOICE => 'invoices',
            self::PAGE_TICKET  => 'tickets',
            self::PAGE_PROJECT => 'projects',
            self::PAGE_ORDER   => 'orders',
            self::PAGE_PROFILE => 'profile',
            self::PAGE_CONTACT => 'contacts',
            self::PAGE_GATEWAY => 'gateways',
        );
    }
}

