<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Process\Toc;

use Bookdown\Bookdown\Content\Heading;
use Bookdown\Bookdown\Content\IndexPage;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Content\TocHeadingIterator;
use Bookdown\Bookdown\Process\ProcessInterface;
use Psr\Log\LoggerInterface;

/**
 *
 * Adds TOC entries to index pages.
 *
 * @package bookdown/bookdown
 *
 */
class TocProcess implements ProcessInterface
{
    /**
     *
     * A logger implementation.
     *
     * @var LoggerInterface
     *
     */
    protected $logger;

    /**
     *
     * The TOC entries for the index page.
     *
     * @var array
     *
     */
    protected $tocEntries;

    /**
     *
     * Only process entries to this depth past the current level.
     *
     * A depth of 0 means render all headings.
     *
     * @var int
     *
     */
    protected $tocDepth;

    /**
     *
     * The maximum (deepest) level to process through.
     *
     * @var int
     *
     */
    protected $maxLevel;

    /**
     *
     * Constructor.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     *
     * Invokes the processor.
     *
     * @param Page $page The Page to process.
     *
     */
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

        $this->addNestedTocEntries($page);
    }

    /**
     *
     * Adds TOC entries to the index page.
     *
     * @param IndexPage $index
     *
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
     *
     * Adds a single TOC entry, as long as it's before the max level allowed.
     *
     * @param Heading $heading A Heading for a TOC entry.
     *
     */
    protected function addTocEntry(Heading $heading)
    {
        if (! $this->tocDepth || $heading->getLevel() <= $this->maxLevel) {
            $this->tocEntries[] = $heading;
        }
    }

    /**
     * @param IndexPage $index
     */
    protected function addNestedTocEntries(IndexPage $index)
    {
        $index->setNestedTocEntries(new TocHeadingIterator($this->tocEntries));
    }
}
