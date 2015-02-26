<?php
namespace Bookdown\Bookdown\Process\Toc;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Content\IndexPage;
use Bookdown\Bookdown\Process\ProcessInterface;

class TocProcess implements ProcessInterface
{
    protected $stdio;
    protected $tocEntries;

    public function __construct(Stdio $stdio)
    {
        $this->stdio = $stdio;
    }

    public function __invoke(Page $page)
    {
        if (! $page->isIndex()) {
            $this->stdio->outln("Skipping TOC entries for non-index {$page->getTarget()}");
            return;
        }

        $this->stdio->outln("Adding TOC entries for {$page->getTarget()}");
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
