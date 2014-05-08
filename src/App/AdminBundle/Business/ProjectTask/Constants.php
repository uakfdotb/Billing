<?php
namespace App\AdminBundle\Business\ProjectTask;

class Constants
{
    const UNIT_FIXED_FEE   = 1;
    const UNIT_HOURLY_RATE = 2;

    public static function getUnits()
    {
        return array(
            self::UNIT_FIXED_FEE   => 'Fixed fee',
            self::UNIT_HOURLY_RATE => 'Hourly rate',
        );
    }

    const STATUS_START      = 1;
    const STATUS_INPROGRESS = 2;
    const STATUS_ONHOLD     = 3;
    const STATUS_COMPLETED  = 4;
    const STATUS_INVOICED   = 5;

    public static function getStatus()
    {
        return array(
            self::STATUS_START      => 'Start',
            self::STATUS_INPROGRESS => 'In progress',
            self::STATUS_ONHOLD     => 'On hold',
            self::STATUS_COMPLETED  => 'Completed',
            self::STATUS_INVOICED   => 'Invoiced'
        );
    }
}