<?php
namespace Bookdown\Content;

class ContentList
{
    protected $items = array();

    public function append(ContentItem $item)
    {
        $prev = end($this->items);
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
}
