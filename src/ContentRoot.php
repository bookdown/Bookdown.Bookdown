<?php
namespace Bookdown\Content;

class ContentRoot extends ContentIndex
{
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
        return DIRECTORY_SEPARATOR . 'index.html';
    }
}
