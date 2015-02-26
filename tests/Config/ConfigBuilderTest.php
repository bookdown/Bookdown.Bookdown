<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\FakeFsio;

class ConfigBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $builder;
    protected $fsio;

    protected function setUp()
    {
        $this->fsio = new FakeFsio();
        $this->fsio->files = array(
            '/path/to/bookdown.json' => '{
                "title": "Example Title",
                "content": {
                    "index": "index.md",
                    "foo": "foo.md",
                    "bar": "/bar.md",
                    "baz": "http://example.com/baz.md"
                }
            }'
        );
        $this->builder = new ConfigBuilder($this->fsio);
    }

    public function testNewConfig()
    {
        $config = $this->builder->newConfig('/path/to/bookdown.json');
        $this->assertInstanceOf('Bookdown\Bookdown\Config\Config', $config);
    }

    public function testNewRootConfig()
    {
        $config = $this->builder->newRootConfig('/path/to/bookdown.json');
        $this->assertInstanceOf('Bookdown\Bookdown\Config\RootConfig', $config);
    }
}
