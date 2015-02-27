<?php
namespace Bookdown\Bookdown\Content;

use Bookdown\Bookdown\Config\IndexConfig;

class IndexPage extends Page
{
    protected $children;

    protected $tocEntries;

    protected $config;

    public function __construct(
        IndexConfig $config,
        $name,
        $parent,
        $count
    ) {
        $this->config = $config;
        $this->name = $name;
        $this->parent = $parent;
        $this->count = $count;
        $this->setTitle($config->getTitle());
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getHref()
    {
        $base = $this->getParent()->getHref();
        return $base . $this->getName() . '/';
    }

    public function addChild(Page $child)
    {
        $this->children[] = $child;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getTarget()
    {
        $base = rtrim(
            dirname($this->getParent()->getTarget()),
            DIRECTORY_SEPARATOR
        );

        return $base
            . DIRECTORY_SEPARATOR . $this->getName()
            . DIRECTORY_SEPARATOR . 'index.html';
    }

    public function setTocEntries($tocEntries)
    {
        $this->tocEntries = $tocEntries;
    }

    public function hasTocEntries()
    {
        return (bool) $this->tocEntries;
    }

    public function getTocEntries()
    {
        return $this->tocEntries;
    }

    public function isIndex()
    {
        return true;
    }
}
