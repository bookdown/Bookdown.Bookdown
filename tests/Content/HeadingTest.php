<?php
namespace Bookdown\Bookdown\Content;

class HeadingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HeadingFactory
     */
    protected $headingFactory;

    protected function setUp()
    {
        $this->headingFactory = new HeadingFactory();
    }

    public function testHeadingWithId()
    {
        /* @var Heading $heading */
        $heading = $this->headingFactory->newInstance(
            '1.2.3.',
            'Example Heading',
            '/foo/bar/baz',
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
        /* @var Heading $heading */
        $heading = $this->headingFactory->newInstance(
            '1.2.3.',
            'Example Heading',
            '/foo/bar/baz'
        );

        $this->assertSame($heading->getNumber(), '1.2.3.');
        $this->assertSame($heading->getTitle(), 'Example Heading');
        $this->assertSame($heading->getId(), null);
        $this->assertSame($heading->getLevel(), 3);
        $this->assertSame($heading->getHref(), '/foo/bar/baz');
        $this->assertSame($heading->getHrefAnchor(), null);
        $this->assertSame($heading->getAnchor(), null);
    }
}
