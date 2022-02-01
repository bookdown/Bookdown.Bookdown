<?php
namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\BookFixture;
use Bookdown\Bookdown\Container;
use Bookdown\Bookdown\FakeFsio;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class RenderingProcessTest extends TestCase
{
    protected $fsio;
    protected $fixture;

    protected function set_up()
    {
        $container = new Container(
            fopen('php://memory', 'w+'),
            fopen('php://memory', 'w+'),
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

        $toc = $builder->newProcess(
            $this->fixture->rootConfig,
            'Copyright'
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
    <meta charset="UTF-8">
    <style>
        nav table {
            width: 100%;
        }

        nav.navheader td.curr,
        nav.navheader th.curr {
            text-align: center;
        }

        nav.navheader td.prev,
        nav.navheader th.prev {
            width: 30%;
            text-align: left;
        }

        nav.navheader td.parent,
        nav.navheader th.parent {
            width: 40%;
            text-align: center;
        }

        nav.navheader td.next,
        nav.navheader th.next {
            text-align: right;
            width: 30%;
        }

        nav.navfooter td.prev,
        nav.navfooter th.prev {
            width: 30%;
            text-align: left;
        }

        nav.navfooter td.parent,
        nav.navfooter th.parent {
            width: 40%;
            text-align: center;
        }

        nav.navfooter td.next,
        nav.navfooter th.next {
            text-align: right;
            width: 30%;
        }
    </style>
</head>
<body>

<nav class="navheader">
    <table>
        <tr>
            <th colspan="3" class="curr">1. Chapter</th>
        </tr>
        <tr>
            <td class="prev"><a href="/">Example Book</a></td>
            <td class="parent">Example Book</th>
            <td class="next"><a href="/chapter/section.html">Title</a></td>
        </tr>
    </table>
</nav>

<h1>1. Chapter</h1>
<dl>
<dt>1.1. <a href="/chapter/section.html#1-1">Title</a></dt>
<dd><dl>
<dt>1.1.1. <a href="/chapter/section.html#1-1-1">Subtitle <code>code</code> A</a></dt>
<dd><dl>
<dt>1.1.1.1. <a href="/chapter/section.html#1-1-1-1">Sub-subtitle</a></dt>
</dl></dd>
<dt>1.1.2. <a href="/chapter/section.html#1-1-2">Subtitle B</a></dt>
</dl></dd>
</dl>
<div id="section-main"></div>
<nav class="navfooter">
    <table>
        <tr>
            <td class="prev"><a href="/">Prev</a></td>
            <td class="parent"><a href="/">Up</a></td>
            <td class="next"><a href="/chapter/section.html">Next</a></td>
        </tr>
        <tr>
            <td class="prev">Example Book</td>
            <td class="parent">Example Book</td>
            <td class="next">1.1. Title</td>
        </tr>
        <tr>
            <td class="prev"></td>
            <td class="parent">Copyright (c) 2016 <a href="http://bookdown.io/">Bokdown.io</a></td>
            <td class="next"></td>
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
