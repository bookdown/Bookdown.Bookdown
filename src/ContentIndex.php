<?php
namespace Bookdown\Content;

class ContentIndex extends ContentPage
{
    protected $children;

    public function getAbsoluteHref()
    {
        $base = $this->getParent()->getAbsoluteHref();
        return $base . $this->getName() . '/';
    }

    public function addChild($child)
    {
        $this->children[] = $child;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getOriginData()
    {
        if (! $this->getOrigin()) {
            return '';
        }

        return parent::getOriginData();
    }


    public function getTargetFile()
    {
        $base = rtrim(
            dirname($this->getParent()->getTargetFile()),
            DIRECTORY_SEPARATOR
        );

        return $base
            . DIRECTORY_SEPARATOR . $this->getName()
            . DIRECTORY_SEPARATOR . 'index.html';
    }
}
