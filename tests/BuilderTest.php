<?php
namespace Bookdown\Bookdown;

use Bookdown\Bookdown\Config\RootConfig;

class BuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $builder;
    protected $rootConfig;

    protected function setUp()
    {
        $this->builder = new Builder();

    }

    public function testNewCommand()
    {
        $this->assertInstanceOf(
            'Bookdown\Bookdown\Command',
            $this->builder->newCommand(array())
        );
    }

    public function testNewCollector()
    {
        $this->assertInstanceOf(
            'Bookdown\Bookdown\Collector',
            $this->builder->newCollector()
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
            $this->builder->newProcessor($rootConfig)
        );
    }

    public function testNewProcessUnimplementedBuilder()
    {
        $rootConfig = new RootConfig('/path/to/bookdown.json', '{
            "title": "Example Book",
            "content": {
                "foo": "foo.md"
            },
            "tocProcess": "Bookdown\\\\Bookdown\\\\Proces\\\\Fake\\\\FakeProcessUnimplementedBuilder"
        }');

        $this->setExpectedException(
            'Bookdown\Bookdown\Exception',
            "Bookdown\Bookdown\Proces\Fake\FakeProcessUnimplementedBuilder' does not implement ProcessBuilderInterface"
        );

        $this->builder->newProcess($rootConfig, 'Toc');
    }

    public function testGetCliFactory()
    {
        $factory = $this->builder->getCliFactory();
        $this->assertInstanceOf('Aura\Cli\CliFactory', $factory);
        $again = $this->builder->getCliFactory();
        $this->assertSame($factory, $again);
    }

    public function testGetStdio()
    {
        $stdio = $this->builder->getStdio();
        $this->assertInstanceOf('Aura\Cli\Stdio', $stdio);
        $again = $this->builder->getStdio();
        $this->assertSame($stdio, $again);
    }

    public function testGetFsio()
    {
        $fsio = $this->builder->getFsio();
        $this->assertInstanceOf('Bookdown\Bookdown\Fsio', $fsio);
        $again = $this->builder->getFsio();
        $this->assertSame($fsio, $again);
    }
}
