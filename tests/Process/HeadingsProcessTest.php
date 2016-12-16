<?php
namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\BookFixture;
use Bookdown\Bookdown\Container;
use Bookdown\Bookdown\FakeFsio;

class HeadingsProcessTest extends \PHPUnit_Framework_TestCase
{
    protected $fsio;
    protected $fixture;

    protected function setUp()
    {
        $container = new Container(
            'php://memory',
            'php://memory',
            'Bookdown\Bookdown\FakeFsio'
        );
        $this->fsio = $container->getFsio();

        $this->fixture = new BookFixture($this->fsio);

        $builder = $container->newProcessorBuilder();

        $conversion = $builder->newProcess(
            $this->fixture->rootConfig,
            'Conversion'
        );
        $conversion->__invoke($this->fixture->rootPage);
        $conversion->__invoke($this->fixture->indexPage);
        $conversion->__invoke($this->fixture->page);

        $this->process = $builder->newProcess(
            $this->fixture->rootConfig,
            'Headings'
        );
    }

    public function testHeadings()
    {
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
            array(
                'number' => '1.1.3.',
                'title' => 'Subtitle C',
                'id' => '1.1.3',
                'href' => '/chapter/section.html',
                'level' => 3,
            ),
            array(
                'number' => '1.1.4.',
                'title' => 'Subtitle D',
                'id' => '1.1.4',
                'href' => '/chapter/section.html',
                'level' => 3,
            ),
            array(
                'number' => '1.1.5.',
                'title' => 'Subtitle E',
                'id' => '1.1.5',
                'href' => '/chapter/section.html',
                'level' => 3,
            ),
            array(
                'number' => '1.1.6.',
                'title' => 'Subtitle F',
                'id' => '1.1.6',
                'href' => '/chapter/section.html',
                'level' => 3,
            ),
            array(
                'number' => '1.1.7.',
                'title' => 'Subtitle H',
                'id' => '1.1.7',
                'href' => '/chapter/section.html',
                'level' => 3,
            ),
        );

        $headings = $this->fixture->page->getHeadings();
        $this->assertCount(9, $headings);
        foreach ($headings as $key => $actual) {
            $this->assertSame($expect[$key], $actual->asArray());
        }

        $this->assertSame('Title', $this->fixture->page->getTitle());
    }

    public function testHeadingsOnIndex()
    {
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
}
