<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown;

use Aura\Cli\CliFactory;

/**
 *
 * A package-level Container for Bookdown objects.
 *
 * @package bookdown/bookdown
 *
 */
class Container
{
    /**
     *
     * A stream to STDOUT.
     *
     * @var resource
     *
     */
    protected $stdout;

    /**
     *
     * A stream to STDERR.
     *
     * @var resource
     *
     */
    protected $stderr;

    /**
     *
     * A logger instance.
     *
     * @var LoggerInterface
     *
     */
    protected $logger;

    /**
     *
     * A factory to create CLI objects.
     *
     * @var CliFactory
     *
     */
    protected $cliFactory;

    /**
     *
     * A class name to use for the filesystem I/O operations. Mostly for
     * testing.
     *
     * @var string
     *
     */
    protected $fsioClass;

    /**
     *
     * A filesystem I/O instance.
     *
     * @var Fsio
     *
     */
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

    /**
     *
     * Returns a new Bookdown command object.
     *
     * @param array $globals Typically the PHP $GLOBALS array.
     *
     * @return Command
     *
     */
    public function newCommand($globals)
    {
        return new Command(
            $this->getCliFactory()->newContext($globals),
            $this->getLogger(),
            $this->newService()
        );
    }

    /**
     *
     * Returns a new Bookdown service layer object.
     *
     * @return Service\Service
     *
     */
    public function newService()
    {
        return new Service\Service(
            $this->newCollector(),
            $this->newProcessorBuilder(),
            $this->newTimer()
        );
    }

    /**
     *
     * Returns a new Bookdown page-collector.
     *
     * @return Service\Collector
     *
     */
    public function newCollector()
    {
        return new Service\Collector(
            $this->getLogger(),
            $this->getFsio(),
            new Config\ConfigFactory(),
            new Content\PageFactory()
        );
    }

    /**
     *
     * Returns a new Bookdown builder for processor objects.
     *
     * @return Service\ProcessorBuilder
     *
     */
    public function newProcessorBuilder()
    {
        return new Service\ProcessorBuilder(
            $this->getLogger(),
            $this->getFsio()
        );
    }

    /**
     *
     * Returns a new Bookdown timer.
     *
     * @return Service\Timer
     *
     */
    public function newTimer()
    {
        return new Service\Timer($this->getLogger());
    }

    /**
     *
     * Returns the shared CLI factory object.
     *
     * @return CliFactory
     *
     */
    public function getCliFactory()
    {
        if (! $this->cliFactory) {
            $this->cliFactory = new CliFactory();
        }
        return $this->cliFactory;
    }

    /**
     *
     * Returns the shared logger instance.
     *
     * @return LoggerInterface
     *
     */
    public function getLogger()
    {
        if (! $this->logger) {
            $this->logger = new Stdlog($this->stdout, $this->stderr);
        }

        return $this->logger;
    }

    /**
     *
     * Returns the shared filesystem I/O object.
     *
     * @return Fsio
     *
     */
    public function getFsio()
    {
        if (! $this->fsio) {
            $class = $this->fsioClass;
            $this->fsio = new $class();
        }
        return $this->fsio;
    }
}
