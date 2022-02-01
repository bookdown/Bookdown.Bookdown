<?php
namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\BookFixture;
use Bookdown\Bookdown\BookTocFixture;
use Bookdown\Bookdown\Container;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class TocProcessTest extends TestCase
{
    protected $fsio;
    protected $stdout;
    protected $stderr;
    protected $builder;

    /**
     * @var BookFixture
     */
    protected $fixture;

    protected function set_up()
    {
        $this->stdout = fopen('php://memory', 'a+');
        $this->stderr = fopen('php://memory', 'a+');

        $container = new Container(
            $this->stdout,
            $this->stderr,
            'Bookdown\Bookdown\FakeFsio'
        );
        $this->fsio = $container->getFsio();

        $this->builder = $container->newProcessorBuilder();

    }

    private function setupProcess()
    {
        $conversion = $this->builder->newProcess(
            $this->fixture->rootConfig,
            'Conversion'
        );
        $conversion->__invoke($this->fixture->rootPage);
        $conversion->__invoke($this->fixture->indexPage);
        $conversion->__invoke($this->fixture->page);

        $headings = $this->builder->newProcess(
            $this->fixture->rootConfig,
            'Headings'
        );
        $headings->__invoke($this->fixture->rootPage);
        $headings->__invoke($this->fixture->indexPage);
        $headings->__invoke($this->fixture->page);

        $this->process = $this->builder->newProcess(
            $this->fixture->rootConfig,
            'Toc'
        );
    }

    public function testToc()
    {
        $this->fixture = new BookFixture($this->fsio);
        $this->setupProcess();

        $this->process->__invoke($this->fixture->page);
        rewind($this->stdout);
        $string = '';
        while ($chars = fread($this->stdout, 8192)) {
            $string .= $chars;
        }
        $lines = explode(PHP_EOL, trim($string));
        $actual = trim(end($lines));
        $expect = "Skipping TOC entries for non-index /_site/chapter/section.html";
        $this->assertSame($expect, $actual);
    }

    public function testTocIndex()
    {
        $this->fixture = new BookFixture($this->fsio);
        $this->setupProcess();

        $this->process->__invoke($this->fixture->indexPage);

        $expect = array(
            array(
                'number' => '1.1.',
                'title' => 'Title',
                'id' => '1.1',
                'href' => '/chapter/section.html',
                'hrefAnchor' => '#1-1',
                'level' => 2,
            ),
            array(
                'number' => '1.1.1.',
                'title' => 'Subtitle <code>code</code> A',
                'id' => '1.1.1',
                'href' => '/chapter/section.html',
                'hrefAnchor' => '#1-1-1',
                'level' => 3,
            ),
            array(
                'number' => '1.1.1.1.',
                'title' => 'Sub-subtitle',
                'id' => '1.1.1.1',
                'href' => '/chapter/section.html',
                'hrefAnchor' => '#1-1-1-1',
                'level' => 4,
            ),
            array(
                'number' => '1.1.2.',
                'title' => 'Subtitle B',
                'id' => '1.1.2',
                'href' => '/chapter/section.html',
                'hrefAnchor' => '#1-1-2',
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
        $this->fixture = new BookFixture($this->fsio);
        $this->setupProcess();

        $this->process->__invoke($this->fixture->rootPage);

        $expect = array(
            array(
              'number' => '1.',
              'title' => 'Chapter',
              'id' => null,
              'href' => '/chapter/',
                'hrefAnchor' => '#1',
              'level' => 1,
            ),
            array(
                'number' => '1.1.',
                'title' => 'Title',
                'id' => '1.1',
                'href' => '/chapter/section.html',
                'hrefAnchor' => '#1-1',
                'level' => 2,
            ),
            array(
                'number' => '1.1.1.',
                'title' => 'Subtitle <code>code</code> A',
                'id' => '1.1.1',
                'href' => '/chapter/section.html',
                'hrefAnchor' => '#1-1-1',
                'level' => 3,
            ),
            array(
                'number' => '1.1.1.1.',
                'title' => 'Sub-subtitle',
                'id' => '1.1.1.1',
                'href' => '/chapter/section.html',
                'hrefAnchor' => '#1-1-1-1',
                'level' => 4,
            ),
            array(
                'number' => '1.1.2.',
                'title' => 'Subtitle B',
                'id' => '1.1.2',
                'href' => '/chapter/section.html',
                'hrefAnchor' => '#1-1-2',
                'level' => 3,
            ),
        );

        $headings = $this->fixture->rootPage->getTocEntries();
        $this->assertCount(5, $headings);
        foreach ($headings as $key => $actual) {
            $this->assertSame($expect[$key], $actual->asArray());
        }
    }

    /**
     * @dataProvider providerForTocDepthRoot
     */
    public function testTocDepthRoot($tocDepth, $expect)
    {
        $this->fixture = new BookTocFixture($this->fsio, $tocDepth);
        $this->setupProcess();

        $this->process->__invoke($this->fixture->rootPage);

        $headings = $this->fixture->rootPage->getTocEntries();
        $this->assertCount(count($expect), $headings);
        foreach ($headings as $key => $actual) {
            $this->assertSame($expect[$key], $actual->asArray());
        }
    }

    public function providerForTocDepthRoot()
    {
        return [
            [
                1,
                [
                    [
                        'number' => '1.',
                        'title' => 'Index Page',
                        'id' => null,
                        'href' => '/chapter/',
                        'hrefAnchor' => '#1',
                        'level' => 1,
                    ],
                ],
            ],
            [
                2,
                [
                    [
                        'number' => '1.',
                        'title' => 'Index Page',
                        'id' => null,
                        'href' => '/chapter/',
                        'hrefAnchor' => '#1',
                        'level' => 1,
                    ],
                    [
                        'number' => '1.1.',
                        'title' => 'Title',
                        'id' => '1.1',
                        'href' => '/chapter/section.html',
                        'hrefAnchor' => '#1-1',
                        'level' => 2,
                    ],
                ],
            ],
            [
                3,
                [
                    [
                        'number' => '1.',
                        'title' => 'Index Page',
                        'id' => null,
                        'href' => '/chapter/',
                        'hrefAnchor' => '#1',
                        'level' => 1,
                    ],
                    [
                        'number' => '1.1.',
                        'title' => 'Title',
                        'id' => '1.1',
                        'href' => '/chapter/section.html',
                        'hrefAnchor' => '#1-1',
                        'level' => 2,
                    ],
                    [
                        'number' => '1.1.1.',
                        'title' => 'Subtitle <code>code</code> A',
                        'id' => '1.1.1',
                        'href' => '/chapter/section.html',
                        'hrefAnchor' => '#1-1-1',
                        'level' => 3,
                    ],
                    [
                        'number' => '1.1.2.',
                        'title' => 'Subtitle B',
                        'id' => '1.1.2',
                        'href' => '/chapter/section.html',
                        'hrefAnchor' => '#1-1-2',
                        'level' => 3,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider providerForTocDepthIndex
     */
    public function testTocDepthIndex($tocDepth, $expect)
    {
        $this->fixture = new BookTocFixture($this->fsio, $tocDepth);
        $this->setupProcess();

        $this->process->__invoke($this->fixture->indexPage);

        $headings = $this->fixture->indexPage->getTocEntries();
        $this->assertCount(count($expect), $headings);
        foreach ($headings as $key => $actual) {
            $this->assertSame($expect[$key], $actual->asArray());
        }
    }

    public function providerForTocDepthIndex()
    {
        return [
            [
                1,
                [
                    [
                        'number' => '1.1.',
                        'title' => 'Title',
                        'id' => '1.1',
                        'href' => '/chapter/section.html',
                        'hrefAnchor' => '#1-1',
                        'level' => 2,
                    ],
                ],
            ],
            [
                2,
                [
                    [
                        'number' => '1.1.',
                        'title' => 'Title',
                        'id' => '1.1',
                        'href' => '/chapter/section.html',
                        'hrefAnchor' => '#1-1',
                        'level' => 2,
                    ],
                    [
                        'number' => '1.1.1.',
                        'title' => 'Subtitle <code>code</code> A',
                        'id' => '1.1.1',
                        'href' => '/chapter/section.html',
                        'hrefAnchor' => '#1-1-1',
                        'level' => 3,
                    ],
                    [
                        'number' => '1.1.2.',
                        'title' => 'Subtitle B',
                        'id' => '1.1.2',
                        'href' => '/chapter/section.html',
                        'hrefAnchor' => '#1-1-2',
                        'level' => 3,
                    ],
                ],
            ],
            [
                3,
                [
                    [
                        'number' => '1.1.',
                        'title' => 'Title',
                        'id' => '1.1',
                        'href' => '/chapter/section.html',
                        'hrefAnchor' => '#1-1',
                        'level' => 2,
                    ],
                    [
                        'number' => '1.1.1.',
                        'title' => 'Subtitle <code>code</code> A',
                        'id' => '1.1.1',
                        'href' => '/chapter/section.html',
                        'hrefAnchor' => '#1-1-1',
                        'level' => 3,
                    ],
                    [
                        'number' => '1.1.1.1.',
                        'title' => 'Sub-subtitle A',
                        'id' => '1.1.1.1',
                        'href' => '/chapter/section.html',
                        'hrefAnchor' => '#1-1-1-1',
                        'level' => 4,
                    ],
                    [
                        'number' => '1.1.2.',
                        'title' => 'Subtitle B',
                        'id' => '1.1.2',
                        'href' => '/chapter/section.html',
                        'hrefAnchor' => '#1-1-2',
                        'level' => 3,
                    ],
                    [
                        'number' => '1.1.2.1.',
                        'title' => 'Sub-subtitle B',
                        'id' => '1.1.2.1',
                        'href' => '/chapter/section.html',
                        'hrefAnchor' => '#1-1-2-1',
                        'level' => 4,
                    ],
                ],
            ],
        ];
    }
}
