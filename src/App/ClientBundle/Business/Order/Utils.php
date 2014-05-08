<?php
namespace App\ClientBundle\Business\Order;

class Utils
{
    public static function getPrice($product, $idPaymentTerm)
    {
        switch ($idPaymentTerm) {
            case Constants::SCHEDULE_MONTHLY:
                return array(
                    'setup' => $product->getSetupFeeMonthly(),
                    'price' => $product->getPriceMonthly()
                );
            case Constants::SCHEDULE_QUARTLY:
                return array(
                    'setup' => $product->getSetupFeeQuarterly(),
                    'price' => $product->getPriceQuarterly()
                );
            case Constants::SCHEDULE_SEMI_ANNUALLY:
                return array(
                    'setup' => $product->getSetupFeeSemiAnnually(),
                    'price' => $product->getPriceSemiAnnually()
                );
            case Constants::SCHEDULE_ANNUALLY:
                return array(
                    'setup' => $product->getSetupFeeAnnually(),
                    'price' => $product->getPriceAnnually()
                );
            case Constants::SCHEDULE_BIENNIALY:
                return array(
                    'setup' => $product->getSetupFeeBiennially(),
                    'price' => $product->getPriceBiennially()
                );
            case Constants::SCHEDULE_TRIENNIALY:
                return array(
                    'setup' => $product->getSetupFeeTriennially(),
                    'price' => $product->getPriceTriennially()
                );
        }
        return array('setup' => 0, 'price' => 0);
    }
}