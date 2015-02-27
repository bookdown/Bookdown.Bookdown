<?php
namespace Bookdown\Bookdown\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    protected $config;

    protected $validLocalJson = '{
        "title": "Example Title",
        "content": {
            "foo": "foo.md",
            "bar": "/bar.md",
            "baz": "http://example.com/baz.md"
        }
    }';

    protected $validRemoteJson = '{
        "title": "Example Title",
        "content": {
            "zim": "zim.md",
            "dib": "dib.md",
            "gir": "http://example.com/gir.md"
        }
    }';

    protected $malformedJson = '{';

    protected $jsonMissingTitle = '{
        "content": {
            "foo": "foo.md",
            "bar": "/bar.md",
            "baz": "http://example.com/baz.md"
        }
    }';

    protected $jsonMissingContent = '{
        "title": "Example Title",
        "content" : {}
    }';

    protected function newIndexConfig($file, $data)
    {
        return new IndexConfig($file, $data);
    }

    public function testMalformedJson()
    {
        $this->setExpectedException(
            'Bookdown\Bookdown\Exception',
            "Malformed JSON in '/path/to/bookdown.json'."
        );
        $config = $this->newIndexConfig('/path/to/bookdown.json', $this->malformedJson);
    }

    public function testMissingTitle()
    {
        $this->setExpectedException(
            'Bookdown\Bookdown\Exception',
            "No title set in '/path/to/bookdown.json'."
        );
        $config = $this->newIndexConfig('/path/to/bookdown.json', $this->jsonMissingTitle);
    }

    public function testMissingContent()
    {
        $this->setExpectedException(
            'Bookdown\Bookdown\Exception',
            "No content listed in '/path/to/bookdown.json'."
        );
        $config = $this->newIndexConfig('/path/to/bookdown.json', $this->jsonMissingContent);
    }

    public function testValidLocalJson()
    {
        $config = $this->newIndexConfig('/path/to/bookdown.json', $this->validLocalJson);

        $this->assertSame('/path/to/bookdown.json', $config->getFile());
        $this->assertSame('/path/to/', $config->getDir());
        $this->assertSame('Example Title', $config->getTitle());
        $expect = array(
            'foo' => '/path/to/foo.md',
            'bar' => '/bar.md',
            'baz' => 'http://example.com/baz.md',
        );
        $this->assertSame($expect, $config->getContent());
    }

    public function testValidRemoteJson()
    {
        $config = $this->newIndexConfig(
            'http://example.net/path/to/bookdown.json',
            $this->validRemoteJson
        );

        $this->assertSame('http://example.net/path/to/bookdown.json', $config->getFile());

        $expect = array(
            'zim' => 'http://example.net/path/to/zim.md',
            'dib' => 'http://example.net/path/to/dib.md',
            'gir' => 'http://example.com/gir.md',
        );
        $this->assertSame($expect, $config->getContent());
    }

    public function testInvalidRemoteJson()
    {
        $this->setExpectedException(
            'Bookdown\Bookdown\Exception',
            "Cannot handle absolute content path '/bar.md' in remote 'http://example.net/path/to/bookdown.json'."
        );
        $config = $this->newIndexConfig(
            'http://example.net/path/to/bookdown.json',
            $this->validLocalJson
        );
    }
}
