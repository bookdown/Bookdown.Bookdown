<?php
namespace Bookdown\Bookdown\Service;

class Service
{
    protected $collector;
    protected $processorBuilder;
    protected $timer;

    public function __construct(
        Collector $collector,
        ProcessorBuilder $processorBuilder,
        Timer $timer
    ) {
        $this->collector = $collector;
        $this->processorBuilder = $processorBuilder;
        $this->timer = $timer;
    }

    public function __invoke($rootConfigFile, array $configOverrides)
    {
        $rootPage = $this->collector->__invoke($rootConfigFile, $configOverrides);
        $processor = $this->processorBuilder->newProcessor($rootPage->getConfig());
        $processor->__invoke($rootPage);
        $this->timer->report();
    }
}
