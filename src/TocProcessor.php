<?php
namespace Bookdown\Content;

class TocProcessor
{
    protected $tocEntries;

    public function __invoke(ContentPage $page)
    {
        if (! $page instanceof ContentIndex) {
            return;
        }

        $this->tocEntries = array();
        $this->addTocEntries($page);
        $page->setTocEntries($this->tocEntries);
    }

    protected function addTocEntries(ContentIndex $index)
    {
        foreach ($index->getChildren() as $child) {
            $headings = $child->getHeadings();
            foreach ($headings as $heading) {
                $this->tocEntries[] = $heading;
            }
            if ($child instanceof ContentIndex) {
                $this->addTocEntries($child);
            }
        }
    }
}
