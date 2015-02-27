<?php
namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\BookFixture;
use Bookdown\Bookdown\Container;
use Bookdown\Bookdown\FakeFsio;

class TocProcessTest extends \PHPUnit_Framework_TestCase
{
    protected $fsio;
    protected $stdio;
    protected $fixture;

    protected function setUp()
    {
        $container = new Container(
            'php://memory',
            'php://memory',
            'Bookdown\Bookdown\FakeFsio'
        );
        $this->fsio = $container->getFsio();
        $this->stdio = $container->getStdio();

        $this->fixture = new BookFixture($this->fsio);

        $builder = $container->newProcessorBuilder();

        $conversion = $builder->newProcess(
            $this->fixture->rootConfig,
            'Conversion'
        );
        $conversion->__invoke($this->fixture->rootPage);
        $conversion->__invoke($this->fixture->indexPage);
        $conversion->__invoke($this->fixture->page);

        $headings = $builder->newProcess(
            $this->fixture->rootConfig,
            'Headings'
        );
        $headings->__invoke($this->fixture->rootPage);
        $headings->__invoke($this->fixture->indexPage);
        $headings->__invoke($this->fixture->page);

        $this->process = $builder->newProcess(
            $this->fixture->rootConfig,
            'Toc'
        );
    }

    public function testToc()
    {
        $this->process->__invoke($this->fixture->page);
        $stdout = $this->stdio->getStdout();
        $stdout->rewind();
        $string = '';
        while ($chars = $stdout->fread()) {
            $string .= $chars;
        }
        $lines = explode(PHP_EOL, trim($string));
        $actual = trim(end($lines));
        $expect = "Skipping TOC entries for non-index /_site/chapter/section.html";
        $this->assertSame($expect, $actual);
    }

    public function testTocIndex()
    {
        $this->process->__invoke($this->fixture->indexPage);

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
                'title' => 'Subtitle A',
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

        $headings = $this->fixture->indexPage->getTocEntries();
        $this->assertCount(4, $headings);
        foreach ($headings as $key => $actual) {
            $this->assertSame($expect[$key], $actual->asArray());
        }
    }

    public function testTocRoot()
    {
        $this->process->__invoke($this->fixture->rootPage);

        $expect = array(
            array(
              'number' => '1.',
              'title' => 'Chapter',
              'id' => null,
              'href' => '/chapter/',
              'level' => 1,
            ),
            array(
                'number' => '1.1.',
                'title' => 'Title',
                'id' => '1.1',
                'href' => '/chapter/section.html',
                'level' => 2,
            ),
            array(
                'number' => '1.1.1.',
                'title' => 'Subtitle A',
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

        $headings = $this->fixture->rootPage->getTocEntries();
        $this->assertCount(5, $headings);
        foreach ($headings as $key => $actual) {
            $this->assertSame($expect[$key], $actual->asArray());
        }
    }
}
