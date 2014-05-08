<?php
namespace Easy\ParameterBundle\Parameter;
use Symfony\Component\Yaml\Parser;

class Manager
{
    protected $container;
    protected $cache;
    protected $parameterLoaded = array();
    protected $loader;

    public function __construct($container, $config)
    {
        $this->container = $container;
        $this->cache     = $container->get('cache');
        $this->cache->setNamespace('Easy_Parameter');

        $this->loader = $config['loader'];
        $noCache      = isset($config['nocache']) ? true : false;
        if ($this->cache->contains('is_cached') === false || $noCache === true) {
            foreach ($config['parameter'] as $parameterFile) {
                $this->loadParameterFile($parameterFile);
            }
            $this->cache->save('is_cached', true);
        }
    }

    public function loadParameterFile($file)
    {
        $yaml  = new Parser();
        $value = $yaml->parse(file_get_contents($file));
        foreach ($value as $key => $val) {
            $this->cache->save('Parameter_' . $key, $val);
        }
    }

    public function buildForm($parameterKey, &$builder)
    {
        $parameterKey = 'Parameter_' . $parameterKey;
        $this->cache->setNamespace('Easy_Parameter');
        if ($this->cache->contains($parameterKey)) {
            $pConfig          = $this->cache->fetch($parameterKey);
            $parameterHandler = $this->container->get($pConfig['type']);
            $parameterHandler->buildForm($builder, $pConfig['config']);
        }
    }

    public function view($parameterKey, $data)
    {
        $parameterKey = 'Parameter_' . $parameterKey;
        $this->cache->setNamespace('Easy_Parameter');
        if ($this->cache->contains($parameterKey)) {
            $pConfig          = $this->cache->fetch($parameterKey);
            $parameterHandler = $this->container->get($pConfig['type']);
            return $parameterHandler->view($data, $pConfig['config']);
        }
        return '';
    }

    public function getParameter($parameterKey)
    {
        return $this->container->get($this->loader)->getParameter($parameterKey);
    }
}
