<?php
namespace Bookdown\Content;

class TocProcessor
{
    protected $index;
    protected $entries;
    protected $html;
    protected $baseLevel;
    protected $headingFactory;

    public function __invoke(ContentPage $page)
    {
        if (! $page instanceof ContentIndex) {
            return;
        }

        $this->reset($page);
        $this->addEntries($this->index);
        $this->buildHtmlEntries();
        $this->prependHtml();
    }

    protected function reset(ContentIndex $index)
    {
        $this->index = $index;
        $this->entries = array();
        $this->html = array();
        $this->baseLevel = null;
    }

    protected function addEntries(ContentIndex $index)
    {
        foreach ($index->getChildren() as $child) {
            $this->addItem($child);
            if ($child instanceof ContentIndex) {
                $this->addEntries($child);
            }
        }
    }

    protected function addItem($page)
    {
        if (! $page->hasProcessResult('Bookdown\Content\HeadingsProcessor')) {
            return;
        }

        $entries = $page->getProcessResult('Bookdown\Content\HeadingsProcessor');
        foreach ($entries as $entry) {
            $this->entries[] = $entry;
        }
    }

    protected function prependHtml()
    {
        $html = file_get_contents($this->index->getTargetFile());
        $html = implode(PHP_EOL, $this->html) . PHP_EOL . $html;
        file_put_contents($this->index->getTargetFile(), $html);
    }

    protected function buildHtmlEntries()
    {
        $this->html[] = '<h1>' . $this->index->getNumberAndTitle() . '</h1>';
        $this->html[] = '<dl>';

        $entry = reset($this->entries);
        $this->baseLevel = $entry->getLevel();
        $level = $this->baseLevel;
        foreach ($this->entries as $entry) {
            while ($entry->getLevel() > $level) {
                $pad = $this->getPad($level);
                $this->html[] = "$pad<dd><dl>";
                $level ++;
            }

            while ($entry->getLevel() < $level) {
                $level --;
                $pad = $this->getPad($level);
                $this->html[] = "{$pad}</dl></dd>";
            }

            $this->html[] = $this->getPad($level) . "<dt>{$entry->getNumber()} "
                    . "<a href=\"{$entry->getHref()}\">{$entry->getTitle()}</a>"
                    . "</dt>";
        }

        while ($level > $this->baseLevel) {
            $level --;
            $pad = $this->getPad($level);
            $this->html[] = "{$pad}</dl></dd>";
        }

        $this->html[] = '</dl>';
    }

    protected function getPad($level)
    {
        $len = ($level - $this->baseLevel) * 4;
        return str_pad('', $len);
    }
}
