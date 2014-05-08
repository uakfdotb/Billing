<?php
namespace Easy\FormatterBundle\Formatter;

class Formatter
{
    public $container;
    public $formatterConfig = array();

    public function __construct($container, $formatterConfig)
    {
        $this->container       = $container;
        $this->formatterConfig = $formatterConfig;
    }

    public function contains($key)
    {
        return isset($this->formatterConfig[$key]);
    }

    public function format($field, $entity)
    {
        if (isset($this->formatterConfig[$field])) {
            $config        = $this->formatterConfig[$field];
            $formatterType = $this->container->get($config['type']);
            return $formatterType->format($field, $entity, $config['config']);
        }
        return '';
    }
}