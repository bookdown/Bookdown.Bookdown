<?php
namespace Bookdown\Bookdown\Service;

use Bookdown\Bookdown\BookFixture;
use Bookdown\Bookdown\Container;
use Bookdown\Bookdown\Process\Fake\FakeProcess;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class ProcessorTest extends TestCase
{
    protected $root;
    protected $collector;
    protected $logger;
    protected $fsio;

    protected function set_up()
    {
        $container = new Container(
            fopen('php://memory', 'w+'),
            fopen('php://memory', 'w+'),
            'Bookdown\Bookdown\FakeFsio'
        );

        $this->logger = $container->getLogger();
        $this->fsio = $container->getFsio();
        $this->setUpFsio();

        $collector = $container->newCollector();
        $this->root = $collector('/path/to/bookdown.json');

        $this->processes = array(
            new FakeProcess(),
            new FakeProcess(),
            new FakeProcess()
        );
    }

    protected function setUpFsio()
    {
        $this->fsio->put('/path/to/bookdown.json', '{
            "title": "Example Book",
            "content": [
                {"chapter-1": "chapter-1/bookdown.json"}
            ],
            "target": "/_site"
        }');

        $this->fsio->put('/path/to/chapter-1/bookdown.json', '{
            "title": "Chapter 1",
            "content": [
                {"section-1": "section-1.md"}
            ]
        }');
    }

    public function testProcessor()
    {
        $processor = new Processor(
            $this->logger,
            $this->processes
        );

        $processor($this->root);

        $expect = array(
            0 => '/_site/index.html',
            1 => '/_site/chapter-1/index.html',
            2 => '/_site/chapter-1/section-1.html',
        );

        foreach ($this->processes as $process) {
            $this->assertSame($expect, $process->info);
        }
    }
}
