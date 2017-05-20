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

/**
 *
 * Keeps track of execution time.
 *
 * @package bookdown/bookdown
 *
 */
class Timer
{
    /**
     *
     * The starting time.
     *
     * @var int
     *
     */
    protected $start;

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
        $this->start = microtime(true);
    }

    /**
     *
     * Reports the run time to the logger.
     *
     */
    public function report()
    {
        $seconds = microtime(true) - $this->start;
        $seconds = trim(sprintf("%10.2f", $seconds));
        $this->logger->info("Completed in {$seconds} seconds.");
    }
}
