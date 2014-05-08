<?php
namespace App\AdminBundle\Business\ServerGroup;

class Constants
{
    const   TYPE_CPANEL  = 1,
            TYPE_SOLUSVM = 2;

    public static function getServerGroupTypes()
    {
        return array(
            self::TYPE_CPANEL  => 'cPanel/WHM',
            self::TYPE_SOLUSVM => 'SolusVM'
        );
    }
    const   LOGIC_SELECTED  = 0,
            LOGIC_RANDOM    = 1,
            LOGIC_EMPTY     = 2;

    public static function getServerGroupLogic()
    {
        return array(
            self::LOGIC_SELECTED  => 'Fill primary server',
            self::LOGIC_RANDOM    => 'Fill servers randomly',
            self::LOGIC_EMPTY     => 'Fill least full server'
        );
    }
}