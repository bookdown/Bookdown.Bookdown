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
    protected $menuEntries = array();

    public function __construct(
        $origin,
        $name,
        IndexPage $parent,
        $count
    ) {
        $this->origin = $origin;
        $this->name = $name;
        $this->parent = $parent;
        $this->count = $count;
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

    public function getHref()
    {
        $base = $this->getParent()->getHref();
        return $base . $this->getName() . '.html';
    }

    public function getNumber()
    {
        $base = $this->getParent()->getNumber();
        $count = $this->getCount();
        return "{$base}{$count}.";
    }

    public function getNumberAndTitle()
    {
        return trim($this->getNumber() . ' ' . $this->getTitle());
    }

    public function getTarget()
    {
        $base = rtrim(
            dirname($this->getParent()->getTarget()),
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
        while (! $page->isRoot()) {
            $page = $page->getParent();
        }
        return $page;
    }

    /**
     * @return array
     */
    public function getMenuEntries()
    {
        return $this->menuEntries;
    }

    /**
     * @param array $menuEntries
     */
    public function setMenuEntries($menuEntries)
    {
        $this->menuEntries = $menuEntries;
    }
}
