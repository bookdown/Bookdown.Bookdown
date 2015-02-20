<?php
namespace Bookdown\Content;

class ContentList
{
    protected $items = array();

    public function append(ContentItem $item)
    {
        $prev = $this->getLast();
        if ($prev) {
            $prev->setNext($item);
            $item->setPrev($prev);
        }

        $this->items[] = $item;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getFirst()
    {
        return reset($this->items);
    }

    public function getLast()
    {
        return end($this->items);
    }
}
