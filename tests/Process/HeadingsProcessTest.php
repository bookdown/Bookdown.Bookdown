<?php

namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\BookFixture;
use Bookdown\Bookdown\BookNumberingFixture;
use Bookdown\Bookdown\Container;

class HeadingsProcessTest extends \PHPUnit_Framework_TestCase
{
    protected $fsio;
    protected $fixture;
    protected $process;
    protected $builder;

    protected function setUp()
    {
        $container = new Container(
            'php://memory',
            'php://memory',
            'Bookdown\Bookdown\FakeFsio'
        );
        $this->fsio = $container->getFsio();
        $this->builder = $container->newProcessorBuilder();
    }

    protected function initializeProcess()
    {
        $conversion = $this->builder->newProcess(
            $this->fixture->rootConfig,
            'Conversion'
        );
        $conversion->__invoke($this->fixture->rootPage);
        $conversion->__invoke($this->fixture->indexPage);
        $conversion->__invoke($this->fixture->page);

        $this->process = $this->builder->newProcess(
            $this->fixture->rootConfig,
            'Headings'
        );
    }

    public function testHeadings()
    {
        $this->fixture = new BookFixture($this->fsio);
        $this->initializeProcess();

        $this->assertNull($this->fixture->page->getTitle());
        $this->process->__invoke($this->fixture->page);

        $expect = array(
            array(
                'number' => '1.1.',
                'title' => 'Title',
                'id' => '1.1',
                'href' => '/chapter/section.html',
                'level' => 2,
            ),
            array(
                'number' => '1.1.1.',
                'title' => 'Subtitle <code>code</code> A',
                'id' => '1.1.1',
                'href' => '/chapter/section.html',
                'level' => 3,
            ),
            array(
                'number' => '1.1.1.1.',
                'title' => 'Sub-subtitle',
                'id' => '1.1.1.1',
                'href' => '/chapter/section.html',
                'level' => 4,
            ),
            array(
                'number' => '1.1.2.',
                'title' => 'Subtitle B',
                'id' => '1.1.2',
                'href' => '/chapter/section.html',
                'level' => 3,
            ),
        );

        $headings = $this->fixture->page->getHeadings();
        $this->assertCount(4, $headings);
        foreach ($headings as $key => $actual) {
            $this->assertSame($expect[$key], $actual->asArray());
        }

        $this->assertSame('Title', $this->fixture->page->getTitle());
    }

    public function testHeadingsOnIndex()
    {
        $this->fixture = new BookFixture($this->fsio);
        $this->initializeProcess();

        $this->assertSame('Chapter', $this->fixture->indexPage->getTitle());

        $this->process->__invoke($this->fixture->indexPage);
        $headings = $this->fixture->indexPage->getHeadings();
        $this->assertCount(1, $headings);
        $expect = array(
            array(
                'number' => '1.',
                'title' => 'Chapter',
                'id' => null,
                'href' => '/chapter/',
                'level' => 1,
            )
        );
        foreach ($headings as $key => $actual) {
            $this->assertSame($expect[$key], $actual->asArray());
        }

        $this->assertSame('Chapter', $this->fixture->indexPage->getTitle());
    }

    public function testHeadingsWithoutNumbering()
    {
        $this->fixture = new BookNumberingFixture($this->fsio);
        $this->initializeProcess();

        $this->assertNull($this->fixture->page->getTitle());
        $this->process->__invoke($this->fixture->page);

        $reflectionClass = new \ReflectionClass($this->process);
        $reflectionProperty = $reflectionClass->getProperty('html');
        $reflectionProperty->setAccessible(true);

        $this->assertSame('Title', $this->fixture->page->getTitle());

        $expected = <<<EOF
<h1 id="1-1">Title</h1>
<p>Text under title.</p>
<h2 id="1-1-1">Subtitle <code>code</code> A</h2>
<p>Text under subtitle A.</p>
<h3 id="1-1-1-1">Sub-subtitle</h3>
<p>Text under sub-subtitle.</p>
<h2 id="1-1-2">Subtitle B</h2>
<p>Text under subtitle B.</p>

EOF;

        $this->assertSame($expected, $reflectionProperty->getValue($this->process));
    }
}
