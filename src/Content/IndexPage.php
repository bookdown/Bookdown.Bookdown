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

/**
 *
 * Represents an index page and its child pages.
 *
 * @package bookdown/bookdown
 *
 */
class IndexPage extends Page
{
    /**
     *
     * Child pages under this index.
     *
     * @var array
     *
     */
    protected $children;

    /**
     *
     * TOC entries for this index.
     *
     * @var array
     *
     */
    protected $tocEntries;

    /**
     *
     * The config object for this index.
     *
     * @var IndexConfig
     *
     */
    protected $config;

    /**
     *
     * Constructor.
     *
     * @param IndexConfig $config The config for this index page.
     *
     * @param string $name The page name.
     *
     * @param IndexPage $parent The index page above this one.
     *
     * @param int $count This page's position at the current level.
     *
     */
    public function __construct(
        IndexConfig $config,
        $name,
        IndexPage $parent,
        $count
    ) {
        $this->config = $config;
        $this->name = $name;
        $this->parent = $parent;
        $this->count = $count;
        $this->setTitle($config->getTitle());
    }

    /**
     *
     * Returns the config object.
     *
     * @return IndexConfig
     *
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     *
     * Returns the href attribute for this page.
     *
     * @return IndexConfig
     *
     */
    public function getHref()
    {
        $base = $this->getParent()->getHref();
        return $base . $this->getName() . '/';
    }

    /**
     *
     * Adds a child page covered by this index.
     *
     * @param Page $child The child page.
     *
     */
    public function addChild(Page $child)
    {
        $this->children[] = $child;
    }

    /**
     *
     * Returns the child pages covered by this index.
     *
     * @return array
     *
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     *
     * Returns the target file path for output from this index.
     *
     * @return string
     *
     */
    public function getTarget()
    {
        $base = rtrim(
            dirname($this->getParent()->getTarget()),
            DIRECTORY_SEPARATOR
        );

        return $base
            . DIRECTORY_SEPARATOR . $this->getName()
            . DIRECTORY_SEPARATOR . 'index.html';
    }

    /**
     *
     * Sets the TOC entries for this index.
     *
     * @param array $tocEntries The TOC entries.
     *
     */
    public function setTocEntries(array $tocEntries)
    {
        $this->tocEntries = $tocEntries;
    }

    /**
     *
     * Does this index have any TOC entries?
     *
     * @return bool
     *
     */
    public function hasTocEntries()
    {
        return (bool) $this->tocEntries;
    }

    /**
     *
     * Returns the TOC entries for this index.
     *
     * @return array
     *
     */
    public function getTocEntries()
    {
        return $this->tocEntries;
    }

    /**
     *
     * Is this an index page?
     *
     * @return bool
     *
     */
    public function isIndex()
    {
        return true;
    }
}
