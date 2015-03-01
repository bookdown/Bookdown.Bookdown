<?php
namespace Bookdown\Bookdown;

use Aura\Cli\CliFactory;

class Container
{
    protected $stdout;
    protected $stderr;
    protected $stdio;
    protected $cliFactory;
    protected $fsioClass;
    protected $fsio;

    public function __construct(
        $stdout = 'php://stdout',
        $stderr = 'php://stderr',
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
            $this->getStdio(),
            $this->getFsio(),
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
            $this->getStdio(),
            $this->getFsio(),
            new Config\ConfigFactory(),
            new Content\PageFactory()
        );
    }

    public function newProcessorBuilder()
    {
        return new Service\ProcessorBuilder(
            $this->getStdio(),
            $this->getFsio()
        );
    }

    public function newTimer()
    {
        return new Service\Timer($this->getStdio());
    }

    public function getCliFactory()
    {
        if (! $this->cliFactory) {
            $this->cliFactory = new CliFactory();
        }
        return $this->cliFactory;
    }

    public function getStdio()
    {
        if (! $this->stdio) {
            $this->stdio = $this->getCliFactory()->newStdio(
                'php://stdin',
                $this->stdout,
                $this->stderr
            );
        }
        return $this->stdio;
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
