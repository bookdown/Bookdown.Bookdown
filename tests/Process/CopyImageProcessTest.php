<?php
namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\BookImageFixture;
use Bookdown\Bookdown\Container;
use Bookdown\Bookdown\FakeFsio;

class CopyImageProcessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FakeFsio
     */
    protected $fsio;

    /**
     * @var BookImageFixture
     */
    protected $fixture;

    private function initializeBook($fixtureClass)
    {
        $container = new Container(
            fopen('php://memory', 'w+'),
            fopen('php://memory', 'w+'),
            'Bookdown\Bookdown\FakeFsio'
        );
        $this->fsio = $container->getFsio();
        $this->fsio->setFiles(
            array(
                '/path/to/chapter/img/test4.jpg' => '',
                '/path/to/chapter/../img/test5.jpg' => '',
            )
        );

        $this->fixture = new $fixtureClass($this->fsio);

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
            'CopyImage'
        );
    }

    public function testCopyImage()
    {
        $this->initializeBook('Bookdown\Bookdown\BookImageFixture');
        $this->process->__invoke($this->fixture->page);

        $actual = $this->fsio->get($this->fixture->page->getTarget());

        // test absolute URI, nothing changed
        $this->assertContains('<img src="http://test.dev/img/test1.jpg" alt="Build Status">', $actual);
        $this->assertContains('<img src="https://test.dev/test2.jpg" alt="Build Status">', $actual);
        $this->assertContains('<img src="//test.dev/img/test3.jpg" alt="Build Status">', $actual);

        // test replacement
        $this->assertContains('<img src="/chapter/img/test4.jpg" alt="Build Status">', $actual);
        $this->assertContains('<img src="/chapter/img/test5.jpg" alt="Build Status">', $actual);
    }
}
