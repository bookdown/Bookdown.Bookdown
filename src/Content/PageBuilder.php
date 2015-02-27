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
        $config = $this->configBuilder->newIndexConfig($bookdownFile);
        $page = new IndexPage($config, $name, $parent, $count);
        return $page;
    }

    public function newRootPage($bookdownFile)
    {
        $config = $this->configBuilder->newRootConfig($bookdownFile);
        $page = new RootPage($config);
        return $page;
    }
}
