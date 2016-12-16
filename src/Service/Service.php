<?php
namespace Bookdown\Bookdown\Service;

class Service
{
    protected $collector;
    protected $processorBuilder;
    protected $timer;
    protected $assetManager;

    public function __construct(
        Collector $collector,
        ProcessorBuilder $processorBuilder,
        Timer $timer,
        AssetManager $assetManager
    ) {
        $this->collector = $collector;
        $this->processorBuilder = $processorBuilder;
        $this->timer = $timer;
        $this->assetManager = $assetManager;
    }

    public function __invoke($rootConfigFile, array $rootConfigOverrides)
    {
        $this->collector->setRootConfigOverrides($rootConfigOverrides);
        $rootPage = $this->collector->__invoke($rootConfigFile);
        $processor = $this->processorBuilder->newProcessor($rootPage->getConfig());
        $processor->__invoke($rootPage);
        $this->assetManager->flush($rootPage->getConfig());
        $this->timer->report();
    }
}
