<?php
namespace Bookdown\Bookdown\Content;

use Bookdown\Bookdown\FakeFsio;

class RootPageTest extends AbstractPageTest
{
    public function testRootPage()
    {
        $root = $this->pageBuilder->newRootPage('/path/to/bookdown.json');
        $this->assertInstanceOf('Bookdown\Bookdown\Content\RootPage', $root);

        $this->assertSame('/', $root->getHref());
        $this->assertSame('', $root->getNumber());
        $this->assertSame('/path/to/_site/index.html', $root->getTarget());
        $this->assertTrue($root->isRoot());
    }
}
