<?php
namespace Easy\FormatterBundle\Formatter;
use Symfony\Component\Yaml\Parser;

class Manager
{
    protected $container;
    protected $cache;
    protected $formatterLoaded = array();

    public function __construct($container, $config)
    {
        $this->container = $container;
        $this->cache     = $container->get('cache');
        $this->cache->setNamespace('Easy_Formatter');

        $noCache = isset($config['nocache']) ? true : false;
        if ($this->cache->contains('is_cached') === false || $noCache === true) {
            foreach ($config['formatter'] as $formatterFile) {
                $this->loadFormatterFile($formatterFile);
            }
            $this->cache->save('is_cached', true);
        }
    }

    public function loadFormatterFile($file)
    {
        $yaml  = new Parser();
        $value = $yaml->parse(file_get_contents($file));
        foreach ($value as $key => $val) {
            $this->cache->save('Formatter_' . $key, $val);
        }
    }

    public function getFormatter($formatterKey)
    {
        $this->cache->setNamespace('Easy_Formatter');
        if (isset($this->formatterLoaded[$formatterKey])) {
            return $this->formatterLoaded[$formatterKey];
        } else {
            $formatter = NULL;

            if ($this->cache->contains('Formatter_' . $formatterKey)) {
                $formatterInfo = $this->cache->fetch('Formatter_' . $formatterKey);
                $formatter     = new Formatter($this->container, $formatterInfo);

                $this->formatterLoaded[$formatterKey] = $formatter;
            }
            return $formatter;
        }
    }

}
