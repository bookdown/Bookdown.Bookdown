<?php
namespace Bookdown\Bookdown;

use Bookdown\Bookdown\Config\RootConfig;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    protected $container;
    protected $rootConfig;

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

    public function testNewProcessor()
    {
        $rootConfig = new RootConfig('/path/to/bookdown.json', '{
            "title": "Example Book",
            "content": {
                "foo": "foo.md"
            }
        }');

        $this->assertInstanceOf(
            'Bookdown\Bookdown\Processor',
            $this->container->newProcessor($rootConfig)
        );
    }

    public function testNewProcessUnimplementedContainer()
    {
        $rootConfig = new RootConfig('/path/to/bookdown.json', '{
            "title": "Example Book",
            "content": {
                "foo": "foo.md"
            },
            "tocProcess": "Bookdown\\\\Bookdown\\\\Process\\\\Fake\\\\FakeProcessUnimplementedBuilder"
        }');

        $this->setExpectedException(
            'Bookdown\Bookdown\Exception',
            "Bookdown\Bookdown\Process\Fake\FakeProcessUnimplementedBuilder' does not implement ProcessBuilderInterface"
        );

        $this->container->newProcess($rootConfig, 'Toc');
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
