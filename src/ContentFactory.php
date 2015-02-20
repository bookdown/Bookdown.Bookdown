<?php
namespace Bookdown\Content;

class ContentFactory
{
    public function newContentItem($name, $origin, $parent, $count)
    {
        return new ContentItem($name, $origin, $parent, $count);
    }
}
