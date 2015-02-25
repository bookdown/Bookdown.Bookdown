<?php
namespace Bookdown\Bookdown\Content;

class PageCollector
{
    protected $pages = array();
    protected $pageBuilder;

    public function __construct(PageBuilder $pageBuilder)
    {
        $this->pageBuilder = $pageBuilder;
    }

    public function __invoke($bookdownFile, $name = '', $parent = null, $count = 0)
    {
        $index = $this->addIndexPage($bookdownFile, $name, $parent, $count);
        $count = 0;
        foreach ($index->getConfig()->getContent() as $name => $origin) {
            $count ++;
            if (substr($origin, -5) == '.json') {
                $child = $this->__invoke($origin, $name, $index, $count);
            } else {
                $child = $this->addPage($name, $origin, $index, $count);
            }
            $index->addChild($child);
        }

        return $index;
    }

    protected function addPage($name, $origin, $parent, $count)
    {
        $page = $this->pageBuilder->newPage($name, $origin, $parent, $count);
        $this->append($page);
        return $page;
    }

    protected function addIndexPage($bookdownFile, $name, $parent, $count)
    {
        if (! $parent) {
            $page = $this->pageBuilder->newRootPage($bookdownFile, $name);
        } else {
            $page = $this->pageBuilder->newIndexPage($bookdownFile, $name, $parent, $count);
        }
        $this->append($page);
        return $page;
    }

    protected function append(Page $page)
    {
        $prev = end($this->pages);
        if ($prev) {
            $prev->setNext($page);
            $page->setPrev($prev);
        }

        $this->pages[] = $page;
    }
}
