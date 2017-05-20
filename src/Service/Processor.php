<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Service;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Content\RootPage;

/**
 *
 * Applies all processes to a RootPage and all its children.
 *
 * @package bookdown/bookdown
 *
 */
class Processor
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
     * An array of Process objects.
     *
     * @var array
     *
     */
    protected $processes;

    /**
     *
     * Constructor.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param array $processes An array of Process objects.
     *
     */
    public function __construct(
        LoggerInterface $logger,
        array $processes
    ) {
        $this->logger = $logger;
        $this->processes = $processes;
    }

    /**
     *
     * Applies the process objects to the pages.
     *
     * @param RootPage $root The root page.
     *
     */
    public function __invoke(RootPage $root)
    {
        $this->logger->info("Processing content.");
        foreach ($this->processes as $process) {
            $this->logger->info("  Applying " . get_class($process));
            $page = $root;
            while ($page) {
                $process($page);
                $page = $page->getNext();
            }
        }
    }
}
