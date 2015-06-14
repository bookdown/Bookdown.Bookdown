<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\FakeFsio;

class ConfigFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $factory;

    protected $file = '/path/to/bookdown.json';

    protected $data = '{
        "title": "Example Title",
        "content": [
            {"foo": "foo.md"},
            {"bar": "/bar.md"},
            {"baz": "http://example.com/baz.md"}
        ],
        "target": "/_site"
    }';

    protected function setUp()
    {
        $this->factory = new ConfigFactory();
    }

    public function testNewIndexConfig()
    {
        $config = $this->factory->newIndexConfig($this->file, $this->data);
        $this->assertInstanceOf('Bookdown\Bookdown\Config\IndexConfig', $config);
    }

    public function testNewRootConfig()
    {
        $overrides = array();
        $config = $this->factory->newRootConfig($this->file, $this->data, $overrides);
        $this->assertInstanceOf('Bookdown\Bookdown\Config\RootConfig', $config);
    }

    public function testNewRootConfigOverrides()
    {
        $overrides = array(
            'template' => md5(uniqid()),
            'target' => md5(uniqid()),
        );

        $this->factory->setRootConfigOverrides($overrides);
        $config = $this->factory->newRootConfig($this->file, $this->data);
        $this->assertInstanceOf('Bookdown\Bookdown\Config\RootConfig', $config);
        $this->assertSame("/path/to/{$overrides['template']}", $config->getTemplate());
        $this->assertSame("/path/to/{$overrides['target']}/", $config->getTarget());
    }
}
