<?php
namespace App\AdminBundle\Helper;

class Formatter
{
    protected $container;
    public $currencyCode = '$';

    public function __construct($container)
    {
        $this->container = $container;

        $config = $this->container->get('app_admin.helper.common')->getConfig();

        if ($config) {
            $fmt                = new \NumberFormatter('en', \NumberFormatter::CURRENCY);
            $tmp                = $fmt->formatCurrency(0, $config->getBillingCurrency());
            $this->currencyCode = mb_substr($tmp, 0, 1, 'UTF-8');
        }

    }

    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    public function format($value, $type, $options = array())
    {
        $config = $this->container->get('app_admin.helper.common')->getConfig();
        switch ($type) {
            case 'money':
                return $this->getCurrencyCode() . number_format($value, 2);
            case 'date':
                return $value != null && $config !== null ? $value->format($config->getDateFormat()) : '';
            case 'datetime':
                return $value != null && $config !== null ? $value->format($config->getDateFormat() . ' H:i:s') : '';
            case 'datetimekendo':
                return $value != null && $config !== null ? $value->format($config->getDateFormat() . ' H:i:s') : '';
                return $value != null && $config !== null ? $value->format('Y-m-d\TH:i:s.000\Z') : '';
            case 'mapping':
                return $this->container->get('app_admin.helper.mapping')->getMappingTitle($options, $value);
                break;
        }
        return $value;
    }
}