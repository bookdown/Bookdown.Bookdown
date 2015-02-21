<?php
namespace Bookdown\Content;

class ContentItem
{
    protected $name;
    protected $origin;
    protected $parent;
    protected $count;
    protected $prev;
    protected $next;
    protected $title;

    public function __construct(
        $name,
        $origin,
        $title,
        $parent,
        $count
    ) {
        $this->name = $name;
        $this->origin = $origin;
        $this->title = $title;
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

    public function getDepth()
    {
        $depth = 0;
        $item = $this;
        while ($item->hasParent()) {
            $depth ++;
            $item = $item->getParent();
        }
        return $depth;
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
}
