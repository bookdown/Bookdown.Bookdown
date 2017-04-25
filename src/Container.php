<?php
namespace Bookdown\Bookdown;

use Aura\Cli\CliFactory;

class Container
{
    protected $stdout;
    protected $stderr;
    protected $logger;
    protected $cliFactory;
    protected $fsioClass;
    protected $fsio;

    public function __construct(
        $stdout = STDOUT,
        $stderr = STDERR,
        $fsioClass = 'Bookdown\Bookdown\Fsio'
    ) {
        $this->stdout = $stdout;
        $this->stderr = $stderr;
        $this->fsioClass = $fsioClass;
    }

    public function newCommand($globals)
    {
        return new Command(
            $this->getCliFactory()->newContext($globals),
            $this->getLogger(),
            $this->newService()
        );
    }

    public function newService()
    {
        return new Service\Service(
            $this->newCollector(),
            $this->newProcessorBuilder(),
            $this->newTimer()
        );
    }

    public function newCollector()
    {
        return new Service\Collector(
            $this->getLogger(),
            $this->getFsio(),
            new Config\ConfigFactory(),
            new Content\PageFactory()
        );
    }

    public function newProcessorBuilder()
    {
        return new Service\ProcessorBuilder(
            $this->getLogger(),
            $this->getFsio()
        );
    }

    public function newTimer()
    {
        return new Service\Timer($this->getLogger());
    }

    public function getCliFactory()
    {
        if (! $this->cliFactory) {
            $this->cliFactory = new CliFactory();
        }
        return $this->cliFactory;
    }

    public function getLogger()
    {
        if (! $this->logger) {
            $this->logger = new Stdlog($this->stdout, $this->stderr);
        }

        return $this->logger;
    }

    public function getFsio()
    {
        if (! $this->fsio) {
            $class = $this->fsioClass;
            $this->fsio = new $class();
        }
        return $this->fsio;
    }
}
