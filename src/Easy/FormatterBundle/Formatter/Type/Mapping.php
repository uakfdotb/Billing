<?php
namespace Easy\FormatterBundle\Formatter\Type;

use Easy\FormatterBundle\Formatter\FormatterInterface;

class Mapping implements FormatterInterface
{
    protected $easyMapping;

    public function __construct($mapping)
    {
        $this->easyMapping = $mapping;
    }

    public function format($field, $entity, $config)
    {
        if (is_array($entity)) {
            $data = $entity[$field];
        } else {
            $getMethod = 'get' . ucfirst($field);
            $data      = $entity->$getMethod();
        }

        return $this->easyMapping->getMappingTitle($config['mapping'], $data);
    }
}
