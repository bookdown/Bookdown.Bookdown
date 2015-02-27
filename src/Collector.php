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
    protected $configFactory;
    protected $pageFactory;
    protected $stdio;
    protected $fsio;
    protected $level;
    protected $prev;

    public function __construct(
        Stdio $stdio,
        Fsio $fsio,
        ConfigFactory $configFactory,
        PageFactory $pageFactory
    ) {
        $this->stdio = $stdio;
        $this->fsio = $fsio;
        $this->configFactory = $configFactory;
        $this->pageFactory = $pageFactory;
    }

    public function __invoke($configFile, $name = '', $parent = null, $count = 0)
    {
        $this->padln("Collecting content from {$configFile}");
        $this->level ++;
        $index = $this->newIndex($configFile, $name, $parent, $count);
        $this->addContent($index);
        $this->level --;
        return $index;
    }

    protected function newIndex($configFile, $name, $parent, $count)
    {
        if (! $parent) {
            return $this->addRootPage($configFile);
        }

        return $this->addIndexPage($configFile, $name, $parent, $count);
    }

    protected function addContent(IndexPage $index)
    {
        $count = 1;
        foreach ($index->getConfig()->getContent() as $name => $file) {
            $child = $this->newChild($file, $name, $index, $count);
            $index->addChild($child);
            $count ++;
        }
    }

    protected function newChild($file, $name, $index, $count)
    {
        if (substr($file, -5) == '.json') {
            return $this->__invoke($file, $name, $index, $count);
        }

        return $this->addPage($file, $name, $index, $count);
    }

    protected function addPage($origin, $name, $parent, $count)
    {
        $page = $this->pageFactory->newPage($origin, $name, $parent, $count);
        $this->padln("Added page {$page->getOrigin()}");
        return $this->append($page);
    }

    protected function addRootPage($configFile)
    {
        $data = $this->fsio->get($configFile);
        $config = $this->configFactory->newRootConfig($configFile, $data);
        $page = $this->pageFactory->newRootPage($config);
        $this->padln("Added root page from {$configFile}");
        return $this->append($page);
    }

    protected function addIndexPage($configFile, $name, $parent, $count)
    {
        $data = $this->fsio->get($configFile);
        $config = $this->configFactory->newIndexConfig($configFile, $data);
        $page = $this->pageFactory->newIndexPage($config, $name, $parent, $count);
        $this->padln("Added index page from {$configFile}");
        return $this->append($page);
    }

    protected function append(Page $page)
    {
        if ($this->prev) {
            $this->prev->setNext($page);
            $page->setPrev($this->prev);
        }
        $this->pages[] = $page;
        $this->prev = $page;
        return $page;
    }

    protected function padln($str)
    {
        $pad = str_pad('', $this->level * 2);
        $this->stdio->outln("  {$pad}{$str}");
    }
}
