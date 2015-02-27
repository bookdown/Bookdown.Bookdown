<?php
namespace Bookdown\Bookdown;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Config\ConfigFactory;
use Bookdown\Bookdown\Content\IndexPage;
use Bookdown\Bookdown\Content\PageFactory;
use Bookdown\Bookdown\Content\Page;

class Collector
{
    protected $pages = array();
    protected $configBuilder;
    protected $pageBuilder;
    protected $stdio;
    protected $fsio;
    protected $level;

    public function __construct(
        Stdio $stdio,
        Fsio $fsio,
        ConfigFactory $configBuilder,
        PageFactory $pageBuilder
    ) {
        $this->stdio = $stdio;
        $this->fsio = $fsio;
        $this->configBuilder = $configBuilder;
        $this->pageBuilder = $pageBuilder;
    }

    public function __invoke($bookdownFile, $name = '', $parent = null, $count = 0)
    {
        $this->padln("Collecting content from {$bookdownFile}");
        $this->level ++;
        $index = $this->newIndex($bookdownFile, $name, $parent, $count);
        $this->addContent($index);
        $this->level --;
        return $index;
    }

    protected function newIndex($bookdownFile, $name, $parent, $count)
    {
        if (! $parent) {
            return $this->addRootPage($bookdownFile);
        }

        return $this->addIndexPage($bookdownFile, $name, $parent, $count);
    }

    protected function addContent(IndexPage $index)
    {
        $count = 1;
        foreach ($index->getConfig()->getContent() as $name => $origin) {
            $child = $this->newChild($origin, $name, $index, $count);
            $index->addChild($child);
            $count ++;
        }
    }

    protected function newChild($origin, $name, $index, $count)
    {
        if (substr($origin, -5) == '.json') {
            return $this->__invoke($origin, $name, $index, $count);
        }

        return $this->addPage($origin, $name, $index, $count);
    }

    protected function addPage($origin, $name, $parent, $count)
    {
        $page = $this->pageBuilder->newPage($origin, $name, $parent, $count);
        $this->padln("Added page {$page->getOrigin()}");
        return $this->append($page);
    }

    protected function addRootPage($bookdownFile)
    {
        $data = $this->fsio->get($bookdownFile);
        $config = $this->configBuilder->newRootConfig($bookdownFile, $data);
        $page = $this->pageBuilder->newRootPage($config);
        $this->padln("Added root page from {$bookdownFile}");
        return $this->append($page);
    }

    protected function addIndexPage($bookdownFile, $name, $parent, $count)
    {
        $data = $this->fsio->get($bookdownFile);
        $config = $this->configBuilder->newIndexConfig($bookdownFile, $data);
        $page = $this->pageBuilder->newIndexPage($config, $name, $parent, $count);
        $this->padln("Added index page from {$bookdownFile}");
        return $this->append($page);
    }

    protected function append(Page $page)
    {
        $prev = end($this->pages);
        if ($prev) {
            $prev->setNext($page);
            $page->setPrev($prev);
        }

        $this->pages[] = $page;
        return $page;
    }

    protected function padln($str)
    {
        $pad = str_pad('', $this->level * 2);
        $this->stdio->outln("  {$pad}{$str}");
    }
}
