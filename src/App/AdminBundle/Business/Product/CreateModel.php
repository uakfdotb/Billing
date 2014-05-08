<?php
namespace App\AdminBundle\Business\Product;

class CreateModel
{
    public $name;
    public $idType;
    public $serverGroup;
    public $idProductGroup;
    public $idEmail;
    public $stock;
    public $isAvailable;
    public $idPaymentType;

    public $isRedirectUnpaidInvoice;

    public $setupFeeMonthly;
    public $setupFeeQuarterly;
    public $setupFeeSemiAnnually;
    public $setupFeeAnnually;
    public $setupFeeBiennially;
    public $setupFeeTriennially;

    public $priceMonthly;
    public $priceQuarterly;
    public $priceSemiAnnually;
    public $priceAnnually;
    public $priceBiennially;
    public $priceTriennially;

    public $triggerCreate;

    public $color;
    public $features;
    public $moduleSettings;
}
