<?php
namespace Bookdown\Content;

class ContentRoot extends ContentIndex
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

    public function getOriginData()
    {
        return null;
    }

    public function getTargetFile()
    {
        return $this->targetBase . DIRECTORY_SEPARATOR . 'index.html';
    }

    public function setTargetBase($targetBase)
    {
        $this->targetBase = $targetBase;
    }
}
