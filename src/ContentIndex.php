<?php
namespace Bookdown\Content;

class ContentIndex extends ContentItem
{
    public function getAbsoluteHref()
    {
        $base = $this->getParent()->getAbsoluteHref();
        return $base . $this->getName() . '/';
    }
}
