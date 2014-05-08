<?php
namespace Easy\MappingBundle\Mapping;
use Symfony\Component\Yaml\Parser;

class Manager
{
    protected $container;
    protected $cache;
    protected $mappingLoaded = array();

    public function __construct($container, $config)
    {
        $this->container = $container;
        $this->cache     = $container->get('cache');
        $this->cache->setNamespace('Easy_Mapping');

        $noCache = isset($config['nocache']) ? true : false;
        if ($this->cache->contains('is_cached') === false || $noCache === true) {
            foreach ($config['mapping'] as $mappingFile) {
                $this->loadMappingFile($mappingFile);
            }
            $this->cache->save('is_cached', true);
        }
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function loadMappingFile($file)
    {
        $yaml  = new Parser();
        $value = $yaml->parse(file_get_contents($file));
        foreach ($value as $key => $val) {
            $this->cache->save('Mapping_' . $key, $val);
        }
    }

    public function getMapping($mappingKey)
    {
        $this->cache->setNamespace('Easy_Mapping');
        if (isset($this->mappingLoaded[$mappingKey])) {
            return $this->mappingLoaded[$mappingKey];
        } else {
            $mapping = array();

            if ($this->cache->contains('Mapping_' . $mappingKey)) {
                $mappingInfo                      = $this->cache->fetch('Mapping_' . $mappingKey);
                $mapping                          = $this->container->get($mappingInfo['type'])->getMapping($mappingInfo['config']);
                $this->mappingLoaded[$mappingKey] = $mapping;
            }
            return $mapping;
        }
    }

    public function getMappingTitle($mappingKey, $id)
    {
        $mapping = $this->getMapping($mappingKey);
        return isset($mapping[$id]) ? $mapping[$id] : '';
    }
}