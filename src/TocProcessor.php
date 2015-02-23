<?php
namespace Bookdown\Content;

class TocProcessor
{
    protected $index;
    protected $entries;
    protected $html;
    protected $baseLevel;

    public function __invoke(ContentItem $item)
    {
        if (! $item instanceof ContentIndex) {
            return;
        }

        $this->reset($item);
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
            if ($child instanceof ContentIndex) {
                $this->addIndex($child);
            } else {
                $this->addItem($child);
            }
        }
    }

    protected function addIndex($index)
    {
        $number = $index->getNumber();

        $this->entries[] = (object) array(
            'number' => $number,
            'level' => substr_count($number, '.'),
            'id' => '',
            'href' => $index->getAbsoluteHref(),
            'title' => $index->getTitle(),
        );

        $this->addEntries($index);
    }

    protected function addItem($item)
    {
        if (! $item->hasProcessResult('Bookdown\Content\HeadingsProcessor')) {
            return;
        }

        $entrys = $item->getProcessResult('Bookdown\Content\HeadingsProcessor');
        foreach ($entrys as $entry) {
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
        $this->html[] =
            '<h1>' . $this->index->getNumber() . ' '
            . $this->index->getTitle() . '</h1>'
        ;

        $this->html[] = '<dl>';

        $entry = reset($this->entries);
        $this->baseLevel = $entry->level;
        $level = $this->baseLevel;
        foreach ($this->entries as $entry) {
            $level = $this->buildHtmlEntry($level, $entry);
        }

        while ($level > $this->baseLevel) {
            $level = $this->endList($level);
        }

        $this->html[] = '</dl>';
    }

    protected function buildHtmlEntry($level, $entry)
    {
        while ($entry->level > $level) {
            $level = $this->beginList($level);
        }

        while ($entry->level < $level) {
            $level = $this->endList($level);
        }

        $this->html[] = $this->getPad($level) . "<dt>{$entry->number} "
                . "<a href=\"{$entry->href}\">{$entry->title}</a>"
                . "</dt>";

        return $level;
    }

    protected function beginList($level)
    {
        $pad = $this->getPad($level);
        $this->html[] = "$pad<dd><dl>";
        return $level + 1;
    }

    protected function endList($level)
    {
        $level = $level - 1;
        $pad = $this->getPad($level);
        $this->html[] = "{$pad}</dl></dd>";
        return $level;
    }

    protected function getPad($level)
    {
        $len = ($level - $this->baseLevel) * 4;
        return str_pad('', $len);
    }
}
