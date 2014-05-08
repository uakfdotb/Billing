<?php
namespace App\AdminBundle\Business\AddonStore;

class Constants
{
    const ADDON_OWN_DOMAIN_SSL       = 1; // DOMAIN
    const ADDON_BUSINESS_AUTOMATION  = 2; // AUTOMATION
    const ADDON_PCI_GATEWAY          = 3; // PCI
    const ADDON_ADDITIONAL_SPACE     = 4; // SPACE
    const ADDON_ADDITIONAL_EMAILS    = 5; // EMAIL
    const ADDON_TURBOCHARGE          = 6; // TURBO

    public static function getAddonCost($addons)
    {
        switch($addons){
            case 'domain':
                return 10.00;
            case 'automation':
                return 7.50;
            case 'pci':
                return 10.00;
            case 'space':
                return 5.00;
            case 'email':
                return 5.00;
            case 'turbo':
                return 10.00;
            default:
                return false;
        }
    }
}