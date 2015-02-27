<?php
namespace Bookdown\Bookdown\Content;

class RootPage extends IndexPage
{
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
