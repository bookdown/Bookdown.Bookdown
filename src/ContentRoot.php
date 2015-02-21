<?php
namespace Bookdown\Content;

class ContentRoot extends ContentIndex
{
    public function getAbsoluteHref()
    {
        return '/';
    }

    public function getOriginData()
    {
        return null;
    }

    public function getNumber()
    {
        return '';
    }
}
