<?php
namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\BookFixture;
use Bookdown\Bookdown\Container;
use Bookdown\Bookdown\FakeFsio;

class RenderingProcessTest extends \PHPUnit_Framework_TestCase
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

        $headings = $builder->newProcess(
            $this->fixture->rootConfig,
            'Headings'
        );
        $headings->__invoke($this->fixture->rootPage);
        $headings->__invoke($this->fixture->indexPage);
        $headings->__invoke($this->fixture->page);

        $toc = $builder->newProcess(
            $this->fixture->rootConfig,
            'Toc'
        );
        $toc->__invoke($this->fixture->rootPage);
        $toc->__invoke($this->fixture->indexPage);
        $toc->__invoke($this->fixture->page);

        $this->process = $builder->newProcess(
            $this->fixture->rootConfig,
            'Rendering'
        );
    }

    public function testRendering()
    {
        $this->process->__invoke($this->fixture->indexPage);
        $expect = '<html>
<head>
    <title>Chapter</title>
</head>
<body>
<nav class="navheader">
    <table width="100%">
        <tr>
            <th colspan="3" align="center">1. Chapter</th>
        </tr>
        <tr>
            <td width="20%" align="left"><a href="/">Example Book</a></td>
            <td width="60%" align="center">Example Book</th>
            <td width="20%" align="right"><a href="/chapter/section.html">Title</a></td>
        </tr>
    </table>
</nav>

<h1>1. Chapter</h1>
<dl>
<dt>1.1. <a href="/chapter/section.html#1.1">Title</a></dt>
<dd><dl>
<dt>1.1.1. <a href="/chapter/section.html#1.1.1">Subtitle A</a></dt>
<dd><dl>
<dt>1.1.1.1. <a href="/chapter/section.html#1.1.1.1">Sub-subtitle</a></dt>
</dl></dd>
<dt>1.1.2. <a href="/chapter/section.html#1.1.2">Subtitle B</a></dt>
</dl></dd>
</dl>
<nav class="navfooter">
    <table width="100%">
        <tr>
            <td width="40%" align="left"><a href="/">Prev</a></td>
            <td width="20%" align="center"><a href="/">Up</a></td>
            <td width="40%" align="right"><a href="/chapter/section.html">Next</a></td>
        </tr>
        <tr>
            <td width="40%" align="left" valign="top">Example Book</td>
            <td width="20%" align="center" valign="top">Example Book</td>
            <td width="40%" align="right" valign="top">1.1. Title</td>
        </tr>
    </table>
</nav>
</body>
</html>
';
        $actual = $this->fsio->get($this->fixture->indexPage->getTarget());
        $this->assertSame($expect, $actual);
    }
}
