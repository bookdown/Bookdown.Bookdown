<?php
namespace Bookdown\Bookdown\Content;

use Bookdown\Bookdown\Config\Config;

class Page
{
    protected $name;
    protected $origin;
    protected $parent;
    protected $count;
    protected $prev;
    protected $next;
    protected $title;
    protected $headings = array();
    protected $config;

    public function __construct(
        $name,
        $origin,
        $parent,
        $count
    ) {
        $this->name = $name;
        $this->origin = $origin;
        $this->parent = $parent;
        $this->count = $count;
    }

    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getOrigin()
    {
        return $this->origin;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function hasParent()
    {
        return (bool) $this->parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function setPrev($prev)
    {
        $this->prev = $prev;
    }

    public function hasPrev()
    {
        return (bool) $this->prev;
    }

    public function getPrev()
    {
        return $this->prev;
    }

    public function setNext($next)
    {
        $this->next = $next;
    }

    public function hasNext()
    {
        return (bool) $this->next;
    }

    public function getNext()
    {
        return $this->next;
    }

    public function getAbsoluteHref()
    {
        $base = $this->getParent()->getAbsoluteHref();
        return $base . $this->getName() . '.html';
    }

    public function getNumber()
    {
        $base = $this->getParent()->getNumber();
        $count = $this->getCount();
        if (! $count) {
            $count = '';
        }
        return "{$base}{$count}.";
    }

    public function getNumberAndTitle()
    {
        return trim($this->getNumber() . ' ' . $this->getTitle());
    }

    public function getTargetFile()
    {
        $base = rtrim(
            dirname($this->getParent()->getTargetFile()),
            DIRECTORY_SEPARATOR
        );
        return $base . DIRECTORY_SEPARATOR . $this->getName() . '.html';
    }

    public function setHeadings(array $headings)
    {
        $this->headings = $headings;
    }

    public function hasHeadings()
    {
        return (bool) $this->headings;
    }

    public function getHeadings()
    {
        return $this->headings;
    }

    public function isIndex()
    {
        return false;
    }

    public function isRoot()
    {
        return false;
    }

    public function getRoot()
    {
        $page = $this;
        while ($parent = $page->getParent()) {
            $page = $parent;
        }
        return $page;
    }

    public function getParentIndex()
    {
        $page = $this->getParent();
        while (! $page->isIndex()) {
            $page = $page->getParent();
        }
        return $page;
    }
}
