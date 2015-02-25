<?php
namespace Bookdown\Bookdown\Content;

class RootPage extends IndexPage
{
    protected $targetBase;

    public function getAbsoluteHref()
    {
        return '/';
    }

    public function getNumber()
    {
        return '';
    }

    public function getTargetFile()
    {
        return $this->targetBase . DIRECTORY_SEPARATOR . 'index.html';
    }

    public function setTargetBase($targetBase)
    {
        $this->targetBase = $targetBase;
    }

    public function isRoot()
    {
        return true;
    }
}
