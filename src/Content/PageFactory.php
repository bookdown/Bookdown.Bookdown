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
 * A factory for Page objects.
 *
 * @package bookdown/bookdown
 *
 */
class PageFactory
{
    /**
     *
     * Returns a new content Page.
     *
     * @param string $origin The page content origin file.
     *
     * @param string $name The page name.
     *
     * @param IndexPage $parent The parent for the page.
     *
     * @param int $count The page position at the current TOC level.
     *
     * @return Page
     *
     */
    public function newPage($origin, $name, IndexPage $parent, $count)
    {
        return new Page($origin, $name, $parent, $count);
    }

    /**
     *
     * Returns a new IndexPage.
     *
     * @param IndexConfig $config The config for this index page.
     *
     * @param string $name The page name.
     *
     * @param IndexPage $parent The index page above this one.
     *
     * @param int $count The page position at the current TOC level.
     *
     * @return IndexPage
     *
     */
    public function newIndexPage(IndexConfig $config, $name, IndexPage $parent, $count)
    {
        return new IndexPage($config, $name, $parent, $count);
    }

    /**
     *
     * Returns a new RootPage.
     *
     * @param RootConfig $config The root-level config object.
     *
     * @return RootPage
     *
     */
    public function newRootPage(RootConfig $config)
    {
        return new RootPage($config);
    }
}
