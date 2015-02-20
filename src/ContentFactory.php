<?php
namespace Bookdown\Content;

class ContentFactory
{
    public function newContentItem($name, $origin, $title, $parent, $count)
    {
        return new ContentItem($name, $origin, $title, $parent, $count);
    }

    public function newContentIndex($name, $origin, $title, $parent, $count)
    {
        return new ContentIndex($name, $origin, $title, $parent, $count);
    }

    public function newContentRoot($name, $origin, $title, $parent, $count)
    {
        return new ContentRoot($name, $origin, $title, $parent, $count);
    }
}
