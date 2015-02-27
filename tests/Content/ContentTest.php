<?php
namespace Bookdown\Bookdown\Content;

use Bookdown\Bookdown\BookFixture;
use Bookdown\Bookdown\FakeFsio;
use Bookdown\Bookdown\Config\IndexConfig;
use Bookdown\Bookdown\Config\RootConfig;

class ContentTest extends \PHPUnit_Framework_TestCase
{
    protected $pageFactory;
    protected $root;
    protected $index;
    protected $page;

    protected function setUp()
    {
        $bookFixture = new BookFixture(new FakeFsio());
        $this->root = $bookFixture->rootPage;
        $this->index = $bookFixture->indexPage;
        $this->page = $bookFixture->page;
    }

    public function testRootPage()
    {
        $this->assertInstanceOf('Bookdown\Bookdown\Content\RootPage', $this->root);

        $this->assertSame(null, $this->root->getOrigin());
        $this->assertSame('/_site/index.html', $this->root->getTarget());

        $this->assertSame('/', $this->root->getHref());
        $this->assertSame('', $this->root->getNumber());
        $this->assertSame('Example Book', $this->root->getTitle());
        $this->assertSame('Example Book', $this->root->getNumberAndTitle());

        $this->assertTrue($this->root->isRoot());
        $this->assertTrue($this->root->isIndex());
        $this->assertSame($this->root, $this->root->getRoot());

        $this->assertFalse($this->root->hasParent());
        $this->assertNull($this->root->getParent());

        $this->assertSame(array($this->index), $this->root->getChildren());

        $this->assertFalse($this->root->hasPrev());
        $this->assertNull($this->root->getPrev());
        $this->assertTrue($this->root->hasNext());
        $this->assertSame($this->index, $this->root->getNext());

        $this->assertFalse($this->root->hasHeadings());
        $fakeHeadings = array(1, 2, 3);
        $this->root->setHeadings($fakeHeadings);
        $this->assertTrue($this->root->hasHeadings());
        $this->assertSame($fakeHeadings, $this->root->getHeadings());

        $this->assertFalse($this->root->hasTocEntries());
        $fakeTocEntries = array(1, 2, 3);
        $this->root->setTocEntries($fakeTocEntries);
        $this->assertTrue($this->root->hasTocEntries());
        $this->assertSame($fakeTocEntries, $this->root->getTocEntries());
    }

    public function testIndexPage()
    {
        $this->assertInstanceOf('Bookdown\Bookdown\Content\IndexPage', $this->index);

        $this->assertSame(null, $this->index->getOrigin());
        $this->assertSame('/_site/chapter/index.html', $this->index->getTarget());
        $this->assertSame('/chapter/', $this->index->getHref());

        $this->assertSame('1.', $this->index->getNumber());
        $this->assertSame('Chapter', $this->index->getTitle());
        $this->assertSame('1. Chapter', $this->index->getNumberAndTitle());

        $this->assertFalse($this->index->isRoot());
        $this->assertTrue($this->index->isIndex());
        $this->assertSame($this->root, $this->index->getRoot());

        $this->assertTrue($this->index->hasParent());
        $this->assertSame($this->root, $this->index->getParent());

        $this->assertSame(array($this->page), $this->index->getChildren());

        $this->assertTrue($this->index->hasPrev());
        $this->assertSame($this->root, $this->index->getPrev());
        $this->assertTrue($this->index->hasNext());
        $this->assertSame($this->page, $this->index->getNext());

        $this->assertFalse($this->index->hasHeadings());
        $fakeHeadings = array(1, 2, 3);
        $this->index->setHeadings($fakeHeadings);
        $this->assertTrue($this->index->hasHeadings());
        $this->assertSame($fakeHeadings, $this->index->getHeadings());

        $this->assertFalse($this->root->hasTocEntries());
        $fakeTocEntries = array(1, 2, 3);
        $this->root->setTocEntries($fakeTocEntries);
        $this->assertTrue($this->root->hasTocEntries());
        $this->assertSame($fakeTocEntries, $this->root->getTocEntries());
    }

    public function testPage()
    {
        $this->assertInstanceOf('Bookdown\Bookdown\Content\Page', $this->page);

        $this->assertSame('/path/to/chapter/section.md', $this->page->getOrigin());
        $this->assertSame('/_site/chapter/section.html', $this->page->getTarget());

        $this->assertSame('/chapter/section.html', $this->page->getHref());
        $this->assertSame('1.1.', $this->page->getNumber());
        $this->assertNull($this->page->getTitle());
        $this->assertSame('1.1.', $this->page->getNumberAndTitle());
        $this->page->setTitle('Section');
        $this->assertSame('1.1. Section', $this->page->getNumberAndTitle());

        $this->assertFalse($this->page->isRoot());
        $this->assertFalse($this->page->isIndex());
        $this->assertSame($this->root, $this->page->getRoot());

        $this->assertTrue($this->page->hasParent());
        $this->assertSame($this->index, $this->page->getParent());

        $this->assertTrue($this->page->hasPrev());
        $this->assertSame($this->index, $this->page->getPrev());
        $this->assertFalse($this->page->hasNext());
        $this->assertNull($this->page->getNext());

        $this->assertFalse($this->page->hasHeadings());
        $fakeHeadings = array(1, 2, 3);
        $this->page->setHeadings($fakeHeadings);
        $this->assertTrue($this->page->hasHeadings());
        $this->assertSame($fakeHeadings, $this->page->getHeadings());
    }
}
