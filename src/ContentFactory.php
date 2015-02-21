<?php
namespace Bookdown\Content;

class ContentFactory
{
    public function newContentItem($name, $origin, $parent, $count)
    {
        return new ContentItem($name, $origin, $parent, $count);
    }

    public function newContentIndex($name, $origin, $parent, $count)
    {
        return new ContentIndex($name, $origin, $parent, $count);
    }

    public function newContentRoot($name, $origin, $parent, $count)
    {
        return new ContentRoot($name, $origin, $parent, $count);
    }
}
