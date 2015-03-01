<?php
namespace Bookdown\Bookdown\Service;

use Bookdown\Bookdown\Collector;
use Bookdown\Bookdown\ProcessorBuilder;

class Service
{
    protected $collector;
    protected $processorBuilder;
    protected $time;

    public function __construct(
        Collector $collector,
        ProcessorBuilder $processorBuilder
    ) {
        $this->collector = $collector;
        $this->processorBuilder = $processorBuilder;
    }

    public function __invoke($rootConfigFile)
    {
        $started = microtime(true);
        $rootPage = $this->collector->__invoke($rootConfigFile);
        $processor = $this->processorBuilder->newProcessor($rootPage->getConfig());
        $processor->__invoke($rootPage);
        $this->time = microtime(true) - $started;
    }

    public function getTime()
    {
        return $this->time;
    }
}
