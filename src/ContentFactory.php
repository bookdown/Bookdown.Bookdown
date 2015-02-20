<?php
namespace Bookdown\Content;

class ContentFactory
{
    public function newContentItem($name, $origin, $depth, $count)
    {
        return new ContentItem($name, $origin, $depth, $count);
    }
}
