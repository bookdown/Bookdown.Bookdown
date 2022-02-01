<?php
namespace Bookdown\Bookdown\Service;

use Bookdown\Bookdown\BookFixture;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Container;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class ProcessorBuilderTest extends TestCase
{
    protected $container;

    protected function set_up()
    {
        $container = new Container();
        $this->builder = $container->newProcessorBuilder();
    }

    public function testNewProcessor()
    {
        $rootConfig = new RootConfig('/path/to/bookdown.json', '{
            "title": "Example Book",
            "content": [
                {"foo": "foo.md"}
            ],
            "target": "_site/"
        }');

        $this->assertInstanceOf(
            'Bookdown\Bookdown\Service\Processor',
            $this->builder->newProcessor($rootConfig)
        );
    }

    public function testNewProcessUnimplementedContainer()
    {
        $rootConfig = new RootConfig('/path/to/bookdown.json', '{
            "title": "Example Book",
            "content": [
                {"foo": "foo.md"}
            ],
            "target": "_site/",
            "tocProcess": "Bookdown\\\\Bookdown\\\\Process\\\\Fake\\\\FakeProcessUnimplementedBuilder"
        }');

        $this->expectException(
            'Bookdown\Bookdown\Exception',
            "Bookdown\Bookdown\Process\Fake\FakeProcessUnimplementedBuilder' does not implement ProcessBuilderInterface"
        );

        $this->builder->newProcess($rootConfig, 'Toc');
    }
}
