<?php
namespace Bookdown\Bookdown;

use Aura\Cli\CliFactory;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Container
{
    protected $stdout;
    protected $stderr;
    protected $logger;
    protected $cliFactory;
    protected $fsioClass;
    protected $fsio;
    protected $assetManager;

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
            $this->getLogger(),
            $this->newService()
        );
    }

    public function newService()
    {
        return new Service\Service(
            $this->newCollector(),
            $this->newProcessorBuilder(),
            $this->newTimer(),
            $this->getAssetManager()
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
            $this->getFsio(),
            $this->getAssetManager()
        );
    }

    public function newTimer()
    {
        return new Service\Timer($this->getLogger());
    }

    public function newAssetManager()
    {
        return new Service\AssetManager(
            $this->getLogger(),
            $this->getFsio()
        );
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
            $formatter = new LineFormatter('%message%' . PHP_EOL);

            $stderr = new StreamHandler($this->stderr, Logger::ERROR, false);
            $stderr->setFormatter($formatter);

            $stdout = new StreamHandler($this->stdout, Logger::DEBUG, false);
            $stdout->setFormatter($formatter);

            $this->logger = new Logger('Bookdown', array($stderr, $stdout));
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

    public function getAssetManager()
    {
        if(! $this->assetManager) {
            $this->assetManager = $this->newAssetManager();
        }

        return $this->assetManager;
    }
}
