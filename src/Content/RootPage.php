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
        return '/';
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
