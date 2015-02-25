<?php
namespace Bookdown\Bookdown\Content;

use Bookdown\Bookdown\ConfigBuilder;

class PageCollector
{
    protected $pages = array();
    protected $configBuilder;
    protected $pageFactory;
    protected $targetBase;

    public function __construct(
        ConfigBuilder $configBuilder,
        PageFactory $pageFactory,
        $targetBase
    ) {
        $this->configBuilder = $configBuilder;
        $this->pageFactory = $pageFactory;
        $this->targetBase = $targetBase;
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

    protected function newConfig($file)
    {
        $data = file_get_contents($file);
        return $this->configBuilder->newInstance($file, $data);
    }

    protected function addPage($name, $origin, $parent, $count)
    {
        $page = $this->pageFactory->newPage($name, $origin, $parent, $count);
        $this->append($page);
        return $page;
    }

    protected function addIndexPage($bookdownFile, $name, $parent, $count)
    {
        if ($parent) {
            // regular index page
            $config = $this->configBuilder->newConfig($bookdownFile);
            $page = $this->pageFactory->newIndexPage($name, $config->getIndexOrigin(), $parent, $count);
        } else {
            // root page
            $config = $this->configBuilder->newRootConfig($bookdownFile);
            $page = $this->pageFactory->newRootPage($name, $config->getIndexOrigin(), $parent, $count);
            $page->setTargetBase($this->targetBase);
        }

        $page->setTitle($config->getTitle());
        $page->setConfig($config);
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
