<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Content;

use Bookdown\Bookdown\Config\RootConfig;

/**
 *
 * Represents the root-level page and its children.
 *
 * @package bookdown/bookdown
 *
 */
class RootPage extends IndexPage
{
    /**
     *
     * Constructor.
     *
     * @param RootConfig $config The root-level config object.
     *
     */
    public function __construct(RootConfig $config)
    {
        $this->config = $config;
        $this->setTitle($config->getTitle());
    }

    /**
     *
     * Returns the href attribute for this Page.
     *
     * @return string
     *
     */
    public function getHref()
    {
        return $this->config->getRootHref();
    }

    /**
     *
     * Returns the full number for this page.
     *
     * @return string
     *
     */
    public function getNumber()
    {
        return '';
    }

    /**
     *
     * Returns the target file path for the output from this page.
     *
     * @return string
     *
     */
    public function getTarget()
    {
        return $this->getConfig()->getTarget() . 'index.html';
    }

    /**
     *
     * Is this a root page?
     *
     * @return bool
     *
     */
    public function isRoot()
    {
        return true;
    }
}
