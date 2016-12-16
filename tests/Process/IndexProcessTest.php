<?php
namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\BookFixture;
use Bookdown\Bookdown\Container;
use Bookdown\Bookdown\FakeFsio;
use Bookdown\Bookdown\Process\Headings\HeadingsProcess;
use Bookdown\Bookdown\Process\Index\IndexProcess;

class IndexProcessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FakeFsio
     */
    protected $fsio;

    /**
     * @var BookFixture
     */
    protected $fixture;

    /**
     * @var indexProcess
     */
    protected $process;

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

        /* @var IndexProcess $conversion */
        $conversion = $builder->newProcess(
            $this->fixture->rootConfig,
            'Conversion'
        );
        $conversion->__invoke($this->fixture->rootPage);
        $conversion->__invoke($this->fixture->indexPage);
        $conversion->__invoke($this->fixture->page);

        /* @var HeadingsProcess $headings */
        $headings = $builder->newProcess(
            $this->fixture->rootConfig,
            'Headings'
        );
        $headings->__invoke($this->fixture->rootPage);
        $headings->__invoke($this->fixture->indexPage);
        $headings->__invoke($this->fixture->page);

        $this->process = $builder->newProcess(
            $this->fixture->rootConfig,
            'Index'
        );

    }

    public function testIndex()
    {
        $this->process->__invoke($this->fixture->rootPage);
        $this->process->__invoke($this->fixture->indexPage);
        $this->process->__invoke($this->fixture->page);

        $expect = '[{"id":"\/chapter\/section.html#1-1","title":"1.1. Title","content":"Text under title."},{"id":"\/chapter\/section.html#1-1-1","title":"1.1.1. Subtitle code A","content":"Text under subtitle A."},{"id":"\/chapter\/section.html#1-1-1-1","title":"1.1.1.1. Sub-subtitle","content":"Text under sub-subtitle."},{"id":"\/chapter\/section.html#1-1-2","title":"1.1.2. Subtitle B","content":"Text under subtitle B. Blockqoute  th th(center) th(right) td td td "}]';

        $actual = $this->fsio->get('/_site/index.json');
        $this->assertSame($expect, $actual);
    }
}
