<?php
namespace Bookdown\Content;

class ContentIndex extends ContentItem
{
    public function getAbsoluteHref()
    {
        $base = '';
        if ($this->hasParent()) {
            $base = $this->getParent()->getAbsoluteHref();
        }
        return $base . $this->getName() . '/';
    }
}
