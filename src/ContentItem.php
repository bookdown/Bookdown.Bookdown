<?php
namespace Bookdown\Content;

class ContentItem
{
    protected $name;
    protected $origin;
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

    public function getParent()
    {
        return $this->parent;
    }

    public function getDepth()
    {
        $depth = 0;
        $parent = $this->getParent();
        while ($parent) {
            $depth ++;
            $parent = $parent->getParent();
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

    public function setNext($next)
    {
        $this->next = $next;
    }
}
