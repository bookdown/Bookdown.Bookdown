<?php
namespace Bookdown\Content;

class HeadingFactory
{
    public function newInstance($number, $title, $href, $id = null)
    {
        return new Heading($number, $title, $href, $id);
    }
}
