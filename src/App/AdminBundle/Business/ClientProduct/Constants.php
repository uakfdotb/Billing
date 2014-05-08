<?php
namespace App\AdminBundle\Business\ClientProduct;

class Constants
{
    const STATUS_PENDING    = 1;
    const STATUS_ACTIVE     = 2;
    const STATUS_SUSPENDED  = 3;
    const STATUS_TERMINATED = 4;

    public static function getStatuses()
    {
        return array(
            self::STATUS_PENDING        => 'Pending',
            self::STATUS_ACTIVE         => 'Active',
            self::STATUS_SUSPENDED      => 'Suspended',
            self::STATUS_TERMINATED     => 'Terminated'
        );
    }
}