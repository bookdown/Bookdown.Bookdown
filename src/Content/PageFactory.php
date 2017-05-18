<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Content;

use Bookdown\Bookdown\Config\IndexConfig;
use Bookdown\Bookdown\Config\RootConfig;

/**
 *
 *
 *
 * @package bookdown/bookdown
 *
 */
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
