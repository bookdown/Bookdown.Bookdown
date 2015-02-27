<?php
namespace Bookdown\Bookdown\Content;

use Bookdown\Bookdown\Config\ConfigBuilder;
use Bookdown\Bookdown\FakeFsio;

class ContentTest extends \PHPUnit_Framework_TestCase
{
    protected $fsio;
    protected $configBuilder;
    protected $pageBuilder;

    protected $root;
    protected $index;
    protected $page;

    protected function setUp()
    {
        $this->fsio = new FakeFsio;
        $this->configBuilder = new ConfigBuilder($this->fsio);
        $this->pageBuilder = new PageBuilder($this->configBuilder);

        $text = <<<TEXT
# Section 1

This is section 1.

TEXT;

        $this->fsio->put('/path/to/bookdown.json', '{
            "title": "Example Book",
            "content": {
                "chapter-1": "chapter-1/bookdown.json"
            },
            "target": "/_site"
        }');

        $this->fsio->put('/path/to/chapter-1/bookdown.json', '{
            "title": "Chapter 1",
            "content": {
                "section-1": "section-1.md"
            }
        }');

        $this->fsio->put('/path/to/chapter-1/section-1.md', $text);

        $this->root = $this->pageBuilder->newRootPage('/path/to/bookdown.json');

        $this->index = $this->pageBuilder->newIndexPage(
            '/path/to/chapter-1/bookdown.json',
            'chapter-1',
            $this->root,
            1
        );

        $this->root->setNext($this->index);
        $this->root->addChild($this->index);
        $this->index->setPrev($this->root);

        $this->page = $this->pageBuilder->newPage(
            'section-1',
            '/path/to/chapter-1/section-1.md',
            $this->index,
            1
        );

        $this->index->addChild($this->page);
        $this->index->setNext($this->page);
        $this->page->setPrev($this->index);
    }

    public function testRootPage()
    {
        $this->assertInstanceOf('Bookdown\Bookdown\Content\RootPage', $this->root);

        $this->assertSame('', $this->root->getOrigin());
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

        $this->assertSame('', $this->index->getOrigin());
        $this->assertSame('/_site/chapter-1/index.html', $this->index->getTarget());
        $this->assertSame('/chapter-1/', $this->index->getHref());

        $this->assertSame('1.', $this->index->getNumber());
        $this->assertSame('Chapter 1', $this->index->getTitle());
        $this->assertSame('1. Chapter 1', $this->index->getNumberAndTitle());

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

        $this->assertSame('/path/to/chapter-1/section-1.md', $this->page->getOrigin());
        $this->assertSame('/_site/chapter-1/section-1.html', $this->page->getTarget());

        $this->assertSame('/chapter-1/section-1.html', $this->page->getHref());
        $this->assertSame('1.1.', $this->page->getNumber());
        $this->assertNull($this->page->getTitle());
        $this->assertSame('1.1.', $this->page->getNumberAndTitle());
        $this->page->setTitle('Section 1');
        $this->assertSame('1.1. Section 1', $this->page->getNumberAndTitle());

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
