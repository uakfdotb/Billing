<?php

namespace App\AdminBundle\Business\Invoice;


class Constants
{
    const STATUS_PAID = 1;
    const STATUS_UNPAID = 2;
    const STATUS_OVERDUE = 3;
    const STATUS_PROFORMA = 4;
    const STATUS_WRITTEN_OFF = 5;

    public static function getInvoiceStatuses()
    {
        return array(
            self::STATUS_PAID        => 'Paid',
            self::STATUS_UNPAID      => 'Unpaid',
            self::STATUS_OVERDUE     => 'Overdue',
            self::STATUS_PROFORMA    => 'Proforma',
            self::STATUS_WRITTEN_OFF => 'Written-off'
        );
    }
}
