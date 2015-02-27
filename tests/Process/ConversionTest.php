<?php
namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\BookFixture;
use Bookdown\Bookdown\Container;
use Bookdown\Bookdown\FakeFsio;

class ConversionTest extends \PHPUnit_Framework_TestCase
{
    protected $fsio;
    protected $page;
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
        $this->process = $builder->newProcess(
            $this->fixture->rootConfig,
            'Conversion'
        );
    }

    public function testConversion()
    {
        $this->process->__invoke($this->fixture->page);
        $html = $this->fsio->get($this->fixture->page->getTarget());
        $this->assertSame('<h1>Title</h1>', trim($html));
    }

    public function testConversionNoOrigin()
    {
        $this->process->__invoke($this->fixture->rootPage);
        $html = $this->fsio->get($this->fixture->rootPage->getTarget());
        $this->assertSame('', trim($html));
    }
}
