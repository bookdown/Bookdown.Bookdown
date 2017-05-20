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
 * Collects and processes all pages.
 *
 * @package bookdown/bookdown
 *
 */
class Service
{
    /**
     *
     * The page collector.
     *
     * @var Collector
     *
     */
    protected $collector;

    /**
     *
     * A builder for the Processor object.
     *
     * @var ProcessorBuilder
     *
     */
    protected $processorBuilder;

    /**
     *
     * A timer for reporting.
     *
     * @var Timer
     *
     */
    protected $timer;

    /**
     *
     * Constructor.
     *
     * @param Collector $collector The page collector.
     *
     * @param ProcessorBuilder A builder for the Processor object.
     *
     * @param Timer $timer A timer for reporting.
     *
     */
    public function __construct(
        Collector $collector,
        ProcessorBuilder $processorBuilder,
        Timer $timer
    ) {
        $this->collector = $collector;
        $this->processorBuilder = $processorBuilder;
        $this->timer = $timer;
    }

    /**
     *
     * Collects and processes the pages.
     *
     * @param string $rootConfigFile The location of the root config file.
     *
     * @param array $rootConfigOverrides Override values from the command-line
     * options.
     *
     */
    public function __invoke($rootConfigFile, array $rootConfigOverrides)
    {
        $this->collector->setRootConfigOverrides($rootConfigOverrides);
        $rootPage = $this->collector->__invoke($rootConfigFile);
        $processor = $this->processorBuilder->newProcessor($rootPage->getConfig());
        $processor->__invoke($rootPage);
        $this->timer->report();
    }
}
