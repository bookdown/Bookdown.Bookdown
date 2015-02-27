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
        $page = new IndexPage($name, null, $parent, $count);
        $page->setTitle($config->getTitle());
        $page->setConfig($config);
        return $page;
    }

    public function newRootPage($bookdownFile)
    {
        $config = $this->configBuilder->newRootConfig($bookdownFile);
        $page = new RootPage(null, null, null, null);
        $page->setTitle($config->getTitle());
        $page->setConfig($config);
        return $page;
    }
}
