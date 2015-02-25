<?php
namespace Bookdown\Bookdown\Processor;

use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Content\IndexPage;

class TocProcessor
{
    protected $tocEntries;

    public function __invoke(Page $page)
    {
        if (! $page->isIndex()) {
            return;
        }

        $this->tocEntries = array();
        $this->addTocEntries($page);
        $page->setTocEntries($this->tocEntries);
    }

    protected function addTocEntries(IndexPage $index)
    {
        foreach ($index->getChildren() as $child) {
            $headings = $child->getHeadings();
            foreach ($headings as $heading) {
                $this->tocEntries[] = $heading;
            }
            if ($child->isIndex()) {
                $this->addTocEntries($child);
            }
        }
    }
}
