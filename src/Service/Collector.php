<?php
namespace Bookdown\Bookdown\Service;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\ConfigFactory;
use Bookdown\Bookdown\Content\IndexPage;
use Bookdown\Bookdown\Content\PageFactory;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Fsio;

class Collector
{
    protected $pages = array();
    protected $configFactory;
    protected $pageFactory;
    protected $logger;
    protected $fsio;
    protected $level = 0;
    protected $prev;

    public function __construct(
        LoggerInterface $logger,
        Fsio $fsio,
        ConfigFactory $configFactory,
        PageFactory $pageFactory,
        array $configOverrides = array()
    ) {
        $this->logger = $logger;
        $this->fsio = $fsio;
        $this->configFactory = $configFactory;
        $this->pageFactory = $pageFactory;
        $this->configOverrides = $configOverrides;
    }

    public function __invoke($configFile, $name = '', $parent = null, $count = 0)
    {
        $this->padlog("Collecting content from {$configFile}");
        $this->level ++;
        $index = $this->newIndex($configFile, $name, $parent, $count);
        $this->addContent($index);
        $this->level --;
        return $index;
    }

    protected function newIndex($configFile, $name, $parent, $count)
    {
        if (! $parent) {
            return $this->addRootPage($configFile, $this->configOverrides);
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
        $bookdown_json = 'bookdown.json';
        $len = -1 * strlen($bookdown_json);

        if (substr($file, $len) == $bookdown_json) {
            return $this->__invoke($file, $name, $index, $count);
        }

        return $this->addPage($file, $name, $index, $count);
    }

    protected function addPage($origin, $name, $parent, $count)
    {
        $page = $this->pageFactory->newPage($origin, $name, $parent, $count);
        $this->padlog("Added page {$page->getOrigin()}");
        return $this->append($page);
    }

    protected function addRootPage($configFile, $configOverrides)
    {
        $data = $this->fsio->get($configFile);
        $config = $this->configFactory->newRootConfig($configFile, $data, $configOverrides);
        $page = $this->pageFactory->newRootPage($config);
        $this->padlog("Added root page from {$configFile}");
        return $this->append($page);
    }

    protected function addIndexPage($configFile, $name, $parent, $count)
    {
        $data = $this->fsio->get($configFile);
        $config = $this->configFactory->newIndexConfig($configFile, $data);
        $page = $this->pageFactory->newIndexPage($config, $name, $parent, $count);
        $this->padlog("Added index page from {$configFile}");
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

    protected function padlog($str)
    {
        $pad = str_pad('', $this->level * 2);
        $this->logger->info("{$pad}{$str}");
    }
}
