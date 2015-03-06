<?php
namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\BookFixture;
use Bookdown\Bookdown\Container;
use Bookdown\Bookdown\FakeFsio;

class ConversionProcessTest extends \PHPUnit_Framework_TestCase
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
        $this->process = $builder->newProcess(
            $this->fixture->rootConfig,
            'Conversion'
        );
    }

    public function testConversion()
    {
        $this->process->__invoke($this->fixture->page);
        $expect = '<h1>Title</h1>
<p>Text under title.</p>
<h2>Subtitle <code>code</code> A</h2>
<p>Text under subtitle A.</p>
<h3>Sub-subtitle</h3>
<p>Text under sub-subtitle.</p>
<h2>Subtitle B</h2>
<p>Text under subtitle B.</p>
';
        $actual = $this->fsio->get($this->fixture->page->getTarget());
        $this->assertSame($expect, $actual);
    }

    public function testConversionNoOrigin()
    {
        $this->process->__invoke($this->fixture->rootPage);
        $actual = $this->fsio->get($this->fixture->rootPage->getTarget());
        $this->assertSame('', $actual);
    }
}
