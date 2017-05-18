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
 *
 *
 * @package bookdown/bookdown
 *
 */
class RootPage extends IndexPage
{
    public function __construct(RootConfig $config)
    {
        $this->config = $config;
        $this->setTitle($config->getTitle());
    }

    public function getHref()
    {
        return $this->config->getRootHref();
    }

    public function getNumber()
    {
        return '';
    }

    public function getTarget()
    {
        return $this->getConfig()->getTarget() . 'index.html';
    }

    public function isRoot()
    {
        return true;
    }
}
