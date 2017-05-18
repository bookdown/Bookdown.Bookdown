<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Process\Toc;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Content\IndexPage;
use Bookdown\Bookdown\Process\ProcessInterface;

/**
 *
 *
 *
 * @package bookdown/bookdown
 *
 */
class TocProcess implements ProcessInterface
{
    protected $logger;
    protected $tocEntries;
    protected $tocDepth;
    protected $maxLevel;

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

        $this->tocEntries = [];
        $this->tocDepth = $page->getConfig()->getTocDepth();
        $this->maxLevel = $this->tocDepth + $page->getLevel();

        $this->addTocEntries($page);
        $page->setTocEntries($this->tocEntries);
    }

    /**
     *
     * @param IndexPage $index
     */
    protected function addTocEntries(IndexPage $index)
    {
        foreach ($index->getChildren() as $child) {
            $headings = $child->getHeadings();
            foreach ($headings as $heading) {
                $this->addTocEntry($heading);
            }
            if ($child->isIndex()) {
                $this->addTocEntries($child);
            }
        }
    }

    /**
     * A toc depth of 0 means render all headings.
     *
     * @param IndexPage $index
     * @param int $level
     */
    protected function addTocEntry($heading)
    {
        if (! $this->tocDepth || $heading->getLevel() <= $this->maxLevel) {
            $this->tocEntries[] = $heading;
        }
    }
}
