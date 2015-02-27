<?php
namespace Bookdown\Bookdown\Content;

use Bookdown\Bookdown\Config\ConfigBuilder;
use Bookdown\Bookdown\FakeFsio;

abstract class AbstractPageTest extends \PHPUnit_Framework_TestCase
{
    protected $fsio;
    protected $configBuilder;
    protected $pageBuilder;

    protected function setUp()
    {
        $this->fsio = new FakeFsio;
        $this->configBuilder = new ConfigBuilder($this->fsio);
        $this->pageBuilder = new PageBuilder($this->configBuilder);

        $text = <<<TEXT
# Section 1

This is section 1.

TEXT;

        $this->fsio->files = array(
            '/path/to/bookdown.json' => '{
                "title": "Example Book",
                "content": {
                    "chapter-1": "chapter-1/bookdown.json"
                }
            }',
            '/path/to/chapter-1/bookdown.json' => '{
                "title": "Chapter 1",
                "content": {
                    "section-1": "section-1.md",
                }
            }',
            '/path/to/chapter-1/section-1.md' => $text,
        );
    }
}
