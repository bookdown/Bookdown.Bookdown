<?php
namespace Bookdown\Bookdown\Content;

use Bookdown\Bookdown\Config\RootConfig;

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

    public function getAuthors()
    {
        return $this->config->getAuthors();
    }

    public function getEditors()
    {
        return $this->config->getEditors();
    }
}
