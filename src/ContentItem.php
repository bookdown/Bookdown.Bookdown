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

    public function getName()
    {
        return $this->name;
    }

    public function getOrigin()
    {
        return $this->origin;
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
        $base = '';
        if ($this->hasParent()) {
            $base = $this->getParent()->getAbsoluteHref();
        }
        return $base . $this->getName() . '.html';
    }
}
