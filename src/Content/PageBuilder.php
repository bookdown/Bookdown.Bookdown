<?php
namespace Bookdown\Bookdown\Content;

use Bookdown\Bookdown\Config\ConfigBuilder;

class PageBuilder
{
    protected $configBuilder;

    public function __construct(ConfigBuilder $configBuilder)
    {
        $this->configBuilder = $configBuilder;
    }

    public function newPage($name, $origin, $parent, $count)
    {
        return new Page($name, $origin, $parent, $count);
    }

    public function newIndexPage($bookdownFile, $name, $parent, $count)
    {
        $config = $this->configBuilder->newConfig($bookdownFile);
        $origin = $config->getIndexOrigin();
        $page = new IndexPage($name, $origin, $parent, $count);
        $page->setTitle($config->getTitle());
        $page->setConfig($config);
        return $page;
    }

    public function newRootPage($bookdownFile, $name, $targetBase)
    {
        $config = $this->configBuilder->newRootConfig($bookdownFile);
        $origin = $config->getIndexOrigin();
        $page = new RootPage($name, $origin, null, null);
        $page->setTargetBase($targetBase);
        $page->setTitle($config->getTitle());
        $page->setConfig($config);
        return $page;
    }
}
