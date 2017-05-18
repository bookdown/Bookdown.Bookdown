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
 *
 *
 * @package bookdown/bookdown
 *
 */
class Timer
{
    protected $start;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->start = microtime(true);
    }

    public function report()
    {
        $seconds = microtime(true) - $this->start;
        $seconds = trim(sprintf("%10.2f", $seconds));
        $this->logger->info("Completed in {$seconds} seconds.");
    }
}
