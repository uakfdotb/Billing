<?php
namespace Easy\ParameterBundle\Parameter;

interface ParameterInterface
{
    function buildForm(&$builder, $config);

    function view($data, $config);
}
