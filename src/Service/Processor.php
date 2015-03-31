<?php
namespace Bookdown\Bookdown\Service;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Content\RootPage;

class Processor
{
    protected $logger;
    protected $processes;

    public function __construct(
        LoggerInterface $logger,
        array $processes
    ) {
        $this->logger = $logger;
        $this->processes = $processes;
    }

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
