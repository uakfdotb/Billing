<?php
namespace Easy\BlockBundle\Block;

class BlockAbstract
{
    protected $template;
    protected $children = array();
    protected $manager = '';

    public function initialize()
    {
    }

    public function setManager($manager)
    {
        $this->manager = $manager;
        foreach ($this->children as $childBlock) {
            $childBlock->setManager($manager);
        }
    }

    public function getManager()
    {
        return $this->manager;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setChild($alias, $block)
    {
        if (is_string($block)) {
            $block = $this->manager->getBlock($block);
        }

        $this->children[$alias] = $block;
    }

    public function getChild($alias)
    {
        if (isset($this->children[$alias])) {
            return $this->children[$alias];
        }
        return null;
    }

    public function removeChild($alias)
    {
        if (isset($this->children[$alias])) {
            unset($this->children[$alias]);
        }
    }

    public function isHaveChilds()
    {
        return !empty($this->children);
    }

    public function prepareData(&$data)
    {
        $data = get_object_vars($this);
    }

    public function toHtml()
    {
        $data = array();
        $this->prepareData($data);
        $data['children'] = $this->children;
        return $this->getManager()->render($this->getTemplate(), $data);
    }

    public function __sleep()
    {
        $data = get_object_vars($this);
        unset($data['manager']);
        return array_keys($data);
    }
}