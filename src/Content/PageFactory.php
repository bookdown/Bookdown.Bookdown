<?php
namespace Bookdown\Bookdown\Content;

use Bookdown\Bookdown\Config\IndexConfig;
use Bookdown\Bookdown\Config\RootConfig;

class PageFactory
{
    public function newPage($origin, $name, $parent, $count)
    {
        return new Page($origin, $name, $parent, $count);
    }

    public function newIndexPage(IndexConfig $config, $name, $parent, $count)
    {
        return new IndexPage($config, $name, $parent, $count);
    }

    public function newRootPage(RootConfig $config)
    {
        return new RootPage($config);
    }
}
