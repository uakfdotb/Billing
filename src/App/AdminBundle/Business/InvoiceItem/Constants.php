<?php

namespace App\AdminBundle\Business\InvoiceItem;


class Constants
{
    const TYPE_CONSULTANCY = 1;
    const TYPE_HOSTING     = 2;
    const TYPE_CODING      = 3;
    const TYPE_DESIGN      = 4;
    const TYPE_OTHER       = 5;

    public static function getInvoiceStatuses()
    {
        return array(
            self::TYPE_CONSULTANCY  => 'Consultancy',
            self::TYPE_HOSTING      => 'Hosting',
            self::TYPE_CODING       => 'Coding',
            self::TYPE_DESIGN       => 'Design',
            self::TYPE_OTHER        => 'Other'
        );
    }
}