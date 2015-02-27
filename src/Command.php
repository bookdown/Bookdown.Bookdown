<?php
namespace Bookdown\Bookdown;

use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Exception as AnyException;

class Command
{
    protected $rootConfigFile;
    protected $rootPage;
    protected $stdio;
    protected $context;
    protected $started;
    protected $container;
    protected $rootConfig;
    protected $collector;

    public function __construct(
        Context $context,
        Stdio $stdio,
        Collector $collector,
        ProcessorBuilder $processorBuilder
    ) {
        $this->context = $context;
        $this->stdio = $stdio;
        $this->collector = $collector;
        $this->processorBuilder = $processorBuilder;
    }

    public function __invoke()
    {
        try {
            $this->init();
            $this->collect();
            $this->process();
            $this->reportTime();
            return 0;
        } catch (AnyException $e) {
            $this->stdio->errln((string) $e);
            $this->stdio->errln($e->getMessage());
            $code = $e->getCode() ? $e->getCode() : 1;
            return $code;
        }
    }

    protected function init()
    {
        $file = $this->context->argv->get(1);
        if (! $file) {
            throw new Exception(
                "Please enter the path to a bookdown.json file as the first argument."
            );
        }

        $this->rootConfigFile = realpath($file);
        if (! $this->rootConfigFile) {
            throw new Exception(
                "Could not resolve '{$file}' to a real path."
            );
        }

        $this->started = microtime(true);
    }

    protected function collect()
    {
        $this->rootPage = $this->collector->__invoke($this->rootConfigFile);
        $this->rootConfig = $this->rootPage->getConfig();
    }

    protected function process()
    {
        $processor = $this->processorBuilder->newProcessor($this->rootConfig);
        $processor->__invoke($this->rootPage);
    }

    protected function reportTime()
    {
        $seconds = trim(sprintf("%10.2f", microtime(true) - $this->started));
        $this->stdio->outln("Completed in {$seconds} seconds.");
    }
}
