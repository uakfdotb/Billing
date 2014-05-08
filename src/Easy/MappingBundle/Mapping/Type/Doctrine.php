<?php
namespace Easy\MappingBundle\Mapping\Type;

use Easy\MappingBundle\Mapping\MappingTypeInterface;

class Doctrine implements MappingTypeInterface
{
    protected $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getMapping($config)
    {
        $mapping = array();
        if (isset($config['em_namespace'])) {
            $result = $this->doctrine->getRepository($config['repository'], $config['em_namespace'])->findAll();
        } else {
            $result = $this->doctrine->getRepository($config['repository'])->findAll();
        }

        foreach ($result as $row) {
            $getIdMethod = 'get' . ucfirst($config['id']);
            $id          = $row->$getIdMethod();

            $title = '';
            if (isset($config['functionTitle'])) {
                $title = call_user_func($config['functionTitle'], $row);
            } else if (isset($config['title'])) {
                $getTitleMethod = 'get' . ucfirst($config['title']);
                $title          = $row->$getTitleMethod();
            }
            $mapping[$id] = $title;
        }
        return $mapping;
    }
}
