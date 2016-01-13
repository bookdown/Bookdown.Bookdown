<?php
namespace Bookdown\Bookdown\Process\Toc;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Content\IndexPage;
use Bookdown\Bookdown\Process\ProcessInterface;

class TocProcess implements ProcessInterface
{
    protected $logger;
    protected $tocEntries;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Page $page)
    {
        if (! $page->isIndex()) {
            $this->logger->info("    Skipping TOC entries for non-index {$page->getTarget()}");
            return;
        }

        $this->logger->info("    Adding TOC entries for {$page->getTarget()}");
        $this->tocEntries = array();
        // if there are multiple books, ensure correct toc level
        $this->addTocEntries($page, $page->getConfig()->getTocDepth(), $page->isRoot() ? 0 : 1);
        $page->setTocEntries($this->tocEntries);
    }

    /**
     * A toc depth of 0 means render all headings. A toc depth of 1 is a special case
     *
     * @param IndexPage $index
     * @param $tocDepth
     * @param int $level
     */
    protected function addTocEntries(IndexPage $index, $tocDepth, $level = 0)
    {
        $maxLevel = $level + $tocDepth;

        if ($tocDepth !== 1 && $tocDepth && $index->isRoot()) {
            $maxLevel --;
        }

        foreach ($index->getChildren() as $child) {
            $headings = $child->getHeadings();
            foreach ($headings as $heading) {
                if ($tocDepth && $heading->getLevel() > $maxLevel) {
                    continue;
                }
                $this->tocEntries[] = $heading;
            }
            if ($child->isIndex() && $tocDepth !== 1) {
                $this->addTocEntries($child, $tocDepth, $level + 1);
            }
        }
    }
}
