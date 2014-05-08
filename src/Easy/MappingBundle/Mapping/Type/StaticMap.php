<?php
namespace Easy\MappingBundle\Mapping\Type;

use Easy\MappingBundle\Mapping\MappingTypeInterface;

class StaticMap implements MappingTypeInterface
{
    public function getMapping($config)
    {
        $mapping = array();
        if (isset($config['function'])) {
            $mapping = call_user_func($config['function']);
        }
        return $mapping;
    }
}
