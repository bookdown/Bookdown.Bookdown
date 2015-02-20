<?php
namespace Bookdown\Content;

class ContentItem
{
    protected $name;
    protected $origin;
    protected $depth;
    protected $count;

    public function __construct(
        $name,
        $origin,
        $depth,
        $count
    ) {
        $this->name = $name;
        $this->origin = $origin;
        $this->depth = $depth;
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

    public function getDepth()
    {
        return $this->depth;
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
