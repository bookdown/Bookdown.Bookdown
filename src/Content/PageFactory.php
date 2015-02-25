<?php
namespace Bookdown\Bookdown\Content;

class PageFactory
{
    public function newPage($name, $origin, $parent, $count)
    {
        return new Page($name, $origin, $parent, $count);
    }

    public function newIndexPage($name, $origin, $parent, $count)
    {
        return new IndexPage($name, $origin, $parent, $count);
    }

    public function newRootPage($name, $origin, $parent, $count)
    {
        return new RootPage($name, $origin, $parent, $count);
    }
}
