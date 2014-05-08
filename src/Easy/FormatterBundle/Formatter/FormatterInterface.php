<?php
namespace Easy\FormatterBundle\Formatter;

interface FormatterInterface
{
    function format($field, $entity, $config);
}
