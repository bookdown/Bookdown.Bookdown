<?php
namespace Bookdown\Bookdown;

use Bookdown\Bookdown\Config\RootConfig;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class ContainerTest extends TestCase
{
    protected $container;

    protected function set_up()
    {
        $this->container = new Container();
    }

    public function testNewCommand()
    {
        $this->assertInstanceOf(
            'Bookdown\Bookdown\Command',
            $this->container->newCommand(array())
        );
    }

    public function testNewCollector()
    {
        $this->assertInstanceOf(
            'Bookdown\Bookdown\Service\Collector',
            $this->container->newCollector()
        );
    }

    public function testNewProcessorBuilder()
    {
        $this->assertInstanceOf(
            'Bookdown\Bookdown\Service\ProcessorBuilder',
            $this->container->newProcessorBuilder()
        );
    }

    public function testGetCliFactory()
    {
        $factory = $this->container->getCliFactory();
        $this->assertInstanceOf('Aura\Cli\CliFactory', $factory);
        $again = $this->container->getCliFactory();
        $this->assertSame($factory, $again);
    }

    public function testGetLogger()
    {
        $logger = $this->container->getLogger();
        $this->assertInstanceOf('Psr\Log\LoggerInterface', $logger);
        $again = $this->container->getLogger();
        $this->assertSame($logger, $again);
    }

    public function testGetFsio()
    {
        $fsio = $this->container->getFsio();
        $this->assertInstanceOf('Bookdown\Bookdown\Fsio', $fsio);
        $again = $this->container->getFsio();
        $this->assertSame($fsio, $again);
    }
}
