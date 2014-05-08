<?php

namespace App\AdminBundle\Business\Product;


class Constants
{
    const PAYMENT_TYPE_FREE      = 1;
    const PAYMENT_TYPE_ONE_TIME  = 2;
    const PAYMENT_TYPE_RECURRING = 3;

    public static function getPaymentTypes()
    {
        return array(
            self::PAYMENT_TYPE_FREE      => 'Free',
            self::PAYMENT_TYPE_ONE_TIME  => 'One-Time',
            self::PAYMENT_TYPE_RECURRING => 'Recurring',
        );
    }

    const PRODUCT_TYPE_CPANEL  = 1;
    const PRODUCT_TYPE_SOLUSVM = 2;

    public static function getProductTypes()
    {
        return array(
            self::PRODUCT_TYPE_CPANEL  => 'cpanelExtended',
            self::PRODUCT_TYPE_SOLUSVM => 'solusvmExtendedCloud',
        );
    }
}