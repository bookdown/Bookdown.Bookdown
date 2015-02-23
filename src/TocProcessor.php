<?php
namespace Bookdown\Content;

class TocProcessor
{
    protected $index;
    protected $entries;

    public function __invoke(ContentItem $item)
    {
        if (! $item instanceof ContentIndex) {
            return;
        }

        $this->reset($item);
        $this->addEntries($this->index);
        $this->prependHtml();
    }

    protected function reset(ContentIndex $index)
    {
        $this->index = $index;
        $this->entries = array();
    }

    protected function addEntries(ContentIndex $index)
    {
        foreach ($index->getChildren() as $child) {
            if ($child instanceof ContentIndex) {
                $this->addIndex($child);
            } else {
                $this->addItem($child);
            }
        }
    }

    protected function addIndex($index)
    {
        $this->entries[] = $index->getNumber() . ' ' . $index->getTitle();
        $this->addEntries($index);
    }

    protected function addItem($item)
    {
        if (! $item->hasProcessResult('Bookdown\Content\HeadingsProcessor')) {
            return;
        }

        $headings = $item->getProcessResult('Bookdown\Content\HeadingsProcessor');
        foreach ($headings as $heading) {
            $this->entries[] = $heading->number . ' ' . $heading->text;
        }
    }

    protected function prependHtml()
    {
        echo PHP_EOL . $this->index->getTitle() . ':' . PHP_EOL;
        foreach ($this->entries as $entry) {
            echo $entry . PHP_EOL;
        }
        echo PHP_EOL;
    }
}
