<?php
namespace Bookdown\Bookdown;

use Bookdown\Bookdown\Config\RootConfig;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    protected $container;

    protected function setUp()
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
            'Bookdown\Bookdown\Collector',
            $this->container->newCollector()
        );
    }

    public function testNewProcessorBuilder()
    {
        $this->assertInstanceOf(
            'Bookdown\Bookdown\ProcessorBuilder',
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

    public function testGetStdio()
    {
        $stdio = $this->container->getStdio();
        $this->assertInstanceOf('Aura\Cli\Stdio', $stdio);
        $again = $this->container->getStdio();
        $this->assertSame($stdio, $again);
    }

    public function testGetFsio()
    {
        $fsio = $this->container->getFsio();
        $this->assertInstanceOf('Bookdown\Bookdown\Fsio', $fsio);
        $again = $this->container->getFsio();
        $this->assertSame($fsio, $again);
    }
}
