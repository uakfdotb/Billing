<?php
namespace Easy\ParameterBundle\Parameter\Type;

use Easy\ParameterBundle\Parameter\ParameterInterface;

class Date implements ParameterInterface
{
    public $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function buildForm($builder)
    {

    }

    public function view($data)
    {

    }
}
