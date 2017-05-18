<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Service;

/**
 *
 *
 *
 * @package bookdown/bookdown
 *
 */
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

    public function __invoke($rootConfigFile, array $rootConfigOverrides)
    {
        $this->collector->setRootConfigOverrides($rootConfigOverrides);
        $rootPage = $this->collector->__invoke($rootConfigFile);
        $processor = $this->processorBuilder->newProcessor($rootPage->getConfig());
        $processor->__invoke($rootPage);
        $this->timer->report();
    }
}
