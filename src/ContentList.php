<?php
namespace Bookdown\Content;

class ContentList
{
    protected $items = array();
    protected $contentFactory;

    public function __construct(ContentFactory $contentFactory)
    {
        $this->contentFactory = $contentFactory;
    }

    public function fill($bookdownFile, $name = '', $parent = null, $count = 0)
    {
        $base = $this->getBase($bookdownFile);
        $json = $this->getJson($bookdownFile);

        $index = $this->addContentIndex($json, $base, $name, $parent, $count);

        $count = 0;
        foreach ($json->content as $name => $origin) {
            $count ++;
            $origin = $this->fixOrigin($origin, $base);
            if ($this->isJson($origin)) {
                $child = $this->fill($origin, $name, $index, $count);
            } else {
                $child = $this->addContentItem($name, $origin, $index, $count);
            }
        }

        $index->addChild($child);
        return $index;
    }

    public function getItems()
    {
        return $this->items;
    }

    protected function getJson($bookdownFile)
    {
        $data = file_get_contents($bookdownFile);
        $json = json_decode($data);

        if (! $json->content) {
            echo "{$bookdownFile} malformed.";
            exit(1);
        }

        return $json;
    }

    protected function getBase($bookdownFile)
    {
        return dirname($bookdownFile) . DIRECTORY_SEPARATOR;
    }

    protected function fixOrigin($origin, $base)
    {
        if (strpos($origin, '://' !== false)) {
            return;
        }

        if ($origin{0} === DIRECTORY_SEPARATOR) {
            return;
        }

        return $base . ltrim($origin, DIRECTORY_SEPARATOR);
    }

    protected function isJson($origin)
    {
        return substr($origin, -5) == '.json';
    }

    protected function addContentItem($name, $origin, $parent, $count)
    {
        $item = $this->contentFactory->newContentItem($name, $origin, $parent, $count);
        $this->append($item);
        return $item;
    }

    protected function addContentIndex($json, $base, $name, $parent, $count)
    {
        $origin = $base . 'index.md';
        if (isset($json->content->index)) {
            $origin = $json->content->index;
            unset($json->content->index);
        }

        if ($parent) {
            $item = $this->contentFactory->newContentIndex($name, $origin, $parent, $count);
        } else {
            $item = $this->contentFactory->newContentRoot($name, $origin, $parent, $count);
        }

        $item->setTitle($json->title);
        $this->append($item);
        return $item;
    }

    protected function append(ContentItem $item)
    {
        $prev = end($this->items);
        if ($prev) {
            $prev->setNext($item);
            $item->setPrev($prev);
        }

        $this->items[] = $item;
    }
}
