<?php
namespace App\AdminBundle\Business\PermissionGroup;

class Constants
{
    const PAGE_CUSTOMER_CLIENTS     = 10;
    const PAGE_CUSTOMER_PROJECTS    = 11;
    const PAGE_CUSTOMER_ESTIMATES   = 12;
    const PAGE_CUSTOMER_INVOICES    = 13;
    const PAGE_CUSTOMER_RECURRINGS  = 14;
    //const PAGE_CUSTOMER_TICKETS     = 15;
    const PAGE_CUSTOMER_CREDIT_NOTE = 17;

    const PAGE_SUPPLIER_SUPPLIERS          = 40;
    const PAGE_SUPPLIER_ESTIMATE_PURCHASES = 41;
    const PAGE_SUPPLIER_INVENTORY          = 42;

    const PAGE_BANK_ACCOUNT   = 100;
    const PAGE_BANK_TRANSFERS = 101;

    //const PAGE_AUTOMATION_AUTOMATION_GROUPS = 110;
    const PAGE_AUTOMATION_SERVER_GROUPS     = 111;
    const PAGE_AUTOMATION_SERVERS           = 112;
    const PAGE_AUTOMATION_PRODUCT_GROUPS    = 113;
    const PAGE_AUTOMATION_PRODUCTS          = 114;
    const PAGE_AUTOMATION_ORDERS            = 115;

    const PAGE_ADMIN_STAFFS            = 70;
    const PAGE_ADMIN_SETTINGS          = 71;
    const PAGE_ADMIN_GATEWAYS          = 72;
    const PAGE_ADMIN_LOGS              = 73;
    const PAGE_ADMIN_EMAILS            = 76;
    const PAGE_ADMIN_TAX               = 78;
    const PAGE_ADMIN_REPORT            = 79;
    const PAGE_ADMIN_PERMISSION_GROUPS = 80;


    public static function getPermissions()
    {
        return array(
            self::PAGE_CUSTOMER_CLIENTS            => 'Customer - Clients',
            self::PAGE_CUSTOMER_PROJECTS           => 'Customer - Projects',
            self::PAGE_CUSTOMER_ESTIMATES          => 'Customer - Estimates',
            self::PAGE_CUSTOMER_INVOICES           => 'Customer - Invoices',
            self::PAGE_CUSTOMER_RECURRINGS         => 'Customer - Recurring Invoices',
            //self::PAGE_CUSTOMER_TICKETS            => 'Customer - Tickets',
            self::PAGE_CUSTOMER_CREDIT_NOTE        => 'Customer - Credit Note',

            self::PAGE_SUPPLIER_SUPPLIERS          => 'Supplier - Suppliers',
            self::PAGE_SUPPLIER_ESTIMATE_PURCHASES => 'Supplier - Purchases',
            self::PAGE_SUPPLIER_INVENTORY          => 'Supplier - Inventory',

            self::PAGE_BANK_ACCOUNT                => 'Bank - Accounts',
            self::PAGE_BANK_TRANSFERS              => 'Bank - Transfer Funds',

            //self::PAGE_AUTOMATION_AUTOMATION_GROUPS => 'Automation - Automation Groups',
            self::PAGE_AUTOMATION_SERVER_GROUPS     => 'Automation - Server Groups',
            self::PAGE_AUTOMATION_SERVERS           => 'Automation - Servers',
            self::PAGE_AUTOMATION_PRODUCT_GROUPS    => 'Automation - Product Groups',
            self::PAGE_AUTOMATION_PRODUCTS          => 'Automation - Products',
            self::PAGE_AUTOMATION_ORDERS            => 'Automation - Orders',

            self::PAGE_ADMIN_STAFFS                => 'Admin - Staff Members',
            self::PAGE_ADMIN_SETTINGS              => 'Admin - Settings',
            self::PAGE_ADMIN_GATEWAYS              => 'Admin - Payment Gateways',
            self::PAGE_ADMIN_LOGS                  => 'Admin - Logs',
            self::PAGE_ADMIN_EMAILS                => 'Admin - Emails',
            self::PAGE_ADMIN_TAX                   => 'Admin - Tax',
            self::PAGE_ADMIN_REPORT                => 'Admin - Report',
            self::PAGE_ADMIN_PERMISSION_GROUPS     => 'Admin - Permission Groups'
        );
    }

    public static function getPermissionCodes()
    {
        return array(
            self::PAGE_CUSTOMER_CLIENTS            => 'customer/clients',
            self::PAGE_CUSTOMER_PROJECTS           => 'customer/projects',
            self::PAGE_CUSTOMER_ESTIMATES          => 'customer/estimates',
            self::PAGE_CUSTOMER_INVOICES           => 'customer/invoices',
            self::PAGE_CUSTOMER_RECURRINGS         => 'customer/recurring',
            //self::PAGE_CUSTOMER_TICKETS            => 'customer/tickets',
            self::PAGE_CUSTOMER_CREDIT_NOTE        => 'customer/credit_note',

            self::PAGE_SUPPLIER_SUPPLIERS          => 'supplier/suppliers',
            self::PAGE_SUPPLIER_ESTIMATE_PURCHASES => 'supplier/supplier_purchases',
            self::PAGE_SUPPLIER_INVENTORY          => 'supplier/inventory',

            self::PAGE_BANK_ACCOUNT                => 'bank/accounts',
            self::PAGE_BANK_TRANSFERS              => 'bank/transfer',

            //self::PAGE_AUTOMATION_AUTOMATION_GROUPS => 'automation/automation_groups',
            self::PAGE_AUTOMATION_SERVER_GROUPS     => 'automation/server_groups',
            self::PAGE_AUTOMATION_SERVERS           => 'automation/servers',
            self::PAGE_AUTOMATION_PRODUCT_GROUPS    => 'automation/product_groups',
            self::PAGE_AUTOMATION_PRODUCTS          => 'automation/products',
            self::PAGE_AUTOMATION_ORDERS            => 'automation/orders',

            self::PAGE_ADMIN_STAFFS                => 'admin/staff',
            self::PAGE_ADMIN_SETTINGS              => 'admin/settings',
            self::PAGE_ADMIN_GATEWAYS              => 'admin/payment-gateways',
            self::PAGE_ADMIN_LOGS                  => 'admin/logs',
            self::PAGE_ADMIN_EMAILS                => 'admin/emails',
            self::PAGE_ADMIN_TAX                   => 'admin/tax',
            self::PAGE_ADMIN_REPORT                => 'admin/report',
            self::PAGE_ADMIN_PERMISSION_GROUPS     => 'admin/permission_group'
        );
    }
}