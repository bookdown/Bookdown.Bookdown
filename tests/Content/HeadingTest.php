<?php
namespace Bookdown\Bookdown\Content;

class HeadingTest extends \PHPUnit_Framework_TestCase
{
    protected $headingFactory;

    protected function setUp()
    {
        $this->headingFactory = new HeadingFactory();
    }

    public function testHeadingWithId()
    {
        $heading = $this->headingFactory->newInstance(
            '1.2.3.',
            'Example Heading',
            '/foo/bar/baz',
            '#1-2-3',
            '1.2.3'
        );

        $this->assertSame($heading->getNumber(), '1.2.3.');
        $this->assertSame($heading->getTitle(), 'Example Heading');
        $this->assertSame($heading->getId(), '1.2.3');
        $this->assertSame($heading->getLevel(), 3);
        $this->assertSame($heading->getHref(), '/foo/bar/baz#1-2-3');
        $this->assertSame($heading->getHrefAnchor(), '#1-2-3');
        $this->assertSame($heading->getAnchor(), '1-2-3');
    }

    public function testHeadingWithoutId()
    {
        $heading = $this->headingFactory->newInstance(
            '1.2.3.',
            'Example Heading',
            '/foo/bar/baz',
            '#1-2-3'
        );

        $this->assertSame($heading->getNumber(), '1.2.3.');
        $this->assertSame($heading->getTitle(), 'Example Heading');
        $this->assertSame($heading->getId(), null);
        $this->assertSame($heading->getLevel(), 3);
        $this->assertSame($heading->getHref(), '/foo/bar/baz');
        $this->assertSame($heading->getHrefAnchor(), '#1-2-3');
        $this->assertSame($heading->getAnchor(), '1-2-3');
    }

    public function testHeadingWithAnchoredHref()
    {
        $heading = $this->headingFactory->newInstance(
            '1.2.3.',
            'Example Heading',
            '/foo/bar/baz/test.html#7-8-9',
            '#1-2-3'
        );

        $this->assertSame($heading->getNumber(), '1.2.3.');
        $this->assertSame($heading->getTitle(), 'Example Heading');
        $this->assertSame($heading->getId(), null);
        $this->assertSame($heading->getLevel(), 3);
        $this->assertSame($heading->getHref(), '/foo/bar/baz/test.html#7-8-9');
        $this->assertSame($heading->getHrefAnchor(), '#1-2-3');
        $this->assertSame($heading->getAnchor(), '1-2-3');
    }
}
