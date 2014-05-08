<?php
namespace Easy\FormatterBundle\Formatter\Type;

use Easy\FormatterBundle\Formatter\FormatterInterface;

class DateTime implements FormatterInterface
{
    public $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function format($field, $entity, $config)
    {
        if (is_array($entity)) {
            $data = $entity[$field];
        } else {
            $getMethod = 'get' . ucfirst($field);
            $data      = $entity->$getMethod();
        }

        $format = $this->container->get('easy_parameter')->getParameter('SYSTEM_DATETIME_FORMAT');

        return $data->format($format['value'] . ' H:i:s');
    }
}
