<?php
namespace Bookdown\Bookdown;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Content\PageBuilder;
use Bookdown\Bookdown\Content\Page;

class Collector
{
    protected $pages = array();
    protected $pageBuilder;
    protected $stdio;

    public function __construct(Stdio $stdio, PageBuilder $pageBuilder)
    {
        $this->stdio = $stdio;
        $this->pageBuilder = $pageBuilder;
    }

    public function __invoke($bookdownFile, $name = '', $parent = null, $count = 0)
    {
        $this->stdio->outln("  Collecting content from {$bookdownFile}");

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
        $this->stdio->outln("    Added page {$page->getOrigin()}");
        $this->append($page);
        return $page;
    }

    protected function addIndexPage($bookdownFile, $name, $parent, $count)
    {
        if (! $parent) {
            $page = $this->pageBuilder->newRootPage($bookdownFile);
            $this->stdio->outln("    Added root page from {$bookdownFile}");
        } else {
            $page = $this->pageBuilder->newIndexPage($bookdownFile, $name, $parent, $count);
            $this->stdio->outln("    Added index page from {$bookdownFile}");
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
