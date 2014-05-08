<?php
namespace Easy\BlockBundle\Block;
use Symfony\Component\Yaml\Parser;

class Manager
{
    protected $container;
    protected $cache;
    protected $templating;
    protected $blocks = array();
    protected $templates = array();
    protected $pages = array();

    public function getContainer()
    {
        return $this->container;
    }

    public function __construct($container, $templating, $config)
    {
        $this->container  = $container;
        $this->templating = $templating;
        $this->cache      = $container->get('cache');
        $this->cache->setNamespace('Easy_Block');
        $noCache = isset($config['nocache']) ? true : false;

        if ($this->cache->contains('is_cached') === false || $noCache === true) {
            foreach ($config['block'] as $block) {
                $this->loadBlockMappingFromYaml($block);
            }
            foreach ($config['template'] as $template) {
                $this->loadBlockTemplatesFromYaml($template);
            }
            $this->buildBlocks();
            $this->buildBlocks();

            foreach ($config['page'] as $page) {
                $this->loadPagesFromPath($page);
            }
            $this->cache->save('is_cached', true);
        }
    }


    public function buildBlocks()
    {
        foreach ($this->blocks as $type => $data) {
            $class     = '\\' . $data['class'];
            $arguments = $data['arguments'];

            $block = new $class($arguments);
            $block->setManager($this);
            if (isset($this->templates[$type])) {
                $block->setTemplate($this->templates[$type]);
            }
            $block->initialize();

            $this->cache->save('block_' . $type, $block);
        }
    }

    public function getBlock($type)
    {
        $this->cache->setNamespace('Easy_Block');
        if ($this->cache->contains('block_' . $type)) {
            $block = clone $this->cache->fetch('block_' . $type);
            $block->setManager($this);
            return $block;
        }
        return false;
    }

    public function loadBlockMappingFromYaml($file)
    {
        $yaml  = new Parser();
        $value = $yaml->parse(file_get_contents($file));
        foreach ($value as $key => $val) {
            $this->blocks[$key] = $val;
        }
    }

    public function loadBlockTemplatesFromYaml($file)
    {
        $yaml  = new Parser();
        $value = $yaml->parse(file_get_contents($file));
        foreach ($value as $key => $val) {
            $this->templates[$key] = $val;
        }
    }

    public function loadPagesFromPath($path)
    {
        if (is_dir($path)) {
            if ($handle = opendir($path)) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != '.' && $file != '..') {
                        $this->loadPagesFromXml($path . DIRECTORY_SEPARATOR . $file);
                    }
                }
            }
        }
    }

    public function parseBlock($block, $xml)
    {
        foreach ($xml->children() as $key => $childXml) {
            $attributes = $childXml->attributes();

            if ($key == 'reference') {
                $name       = (string)$attributes['name'];
                $childBlock = $block->getChild($name);
                $this->parseBlock($childBlock, $childXml);
            } else if ($key == 'action') {
                $action = (string)$attributes['name'];
                $params = array();
                foreach ($childXml->children() as $key => $valueXml) {
                    $params[$key] = (string)$valueXml; // FIX ME
                }
                call_user_func_array(array($block, $action), $params);
            } else if ($key == 'block') {
                $name       = (string)$attributes['name'];
                $blockType  = (string)$attributes['type'];
                $childBlock = $this->getBlock($blockType);
                $block->setChild($name, $childBlock);
                $this->parseBlock($childBlock, $childXml);
            } else if ($key == 'builder') {
                $service = (string)$attributes['service'];
                $params  = array();
                foreach ($childXml->children() as $key => $valueXml) {
                    $params[$key] = (string)$valueXml; // FIX ME
                }
                $builder         = $this->container->get($service);
                $builder->params = $params;
                $builder->build($block);
            } else if ($key == 'remove') {
                $name = (string)$attributes['name'];
                $block->removeChild($name);
            } else { // SET DATA
                $block->$key = (string)$childXml;
            }
        }
    }

    public function loadPagesFromXml($file)
    {
        $xml = simplexml_load_file($file);
        foreach ($xml->children() as $pageId => $pageXml) {
            $pageAttributes = $pageXml->attributes();
            if (isset($pageAttributes['block'])) {
                $blockId   = (string)$pageAttributes['block'];
                $pageBlock = $this->getBlock($blockId);
            } else {
                $parentPageId = (string)$pageAttributes['page'];
                $pageBlock    = clone $this->getPage($parentPageId);
            }
            $this->parseBlock($pageBlock, $pageXml);

            $this->cache->save('page_' . $pageId, $pageBlock);
        }
    }

    public function getPage($pageId)
    {
        $this->cache->setNamespace('Easy_Block');
        if ($this->cache->contains('page_' . $pageId)) {
            $page = clone $this->cache->fetch('page_' . $pageId);
            $page->setManager($this);
            return $page;
        }
        return false;
    }

    public function render($template, $data)
    {
        return $this->templating->render($template, $data);
    }
}
