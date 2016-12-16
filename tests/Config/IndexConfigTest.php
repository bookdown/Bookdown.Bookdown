<?php
namespace Bookdown\Bookdown\Config;

class IndexConfigTest extends \PHPUnit_Framework_TestCase
{
    protected $config;

    protected $jsonValidLocal = '{
        "title": "Example Title",
        "content": [
            {"foo": "foo.md"},
            {"bar": "/bar.md"},
            {"baz": "http://example.com/baz.md"}
        ]
    }';

    protected $jsonValidRemote = '{
        "title": "Example Title",
        "content": [
            {"zim": "zim.md"},
            {"dib": "dib.md"},
            {"gir": "http://example.com/gir.md"}
        ]
    }';

    protected $malformedJson = '{';

    protected $jsonMissingTitle = '{
        "content": [
            {"foo": "foo.md"},
            {"bar": "/bar.md"},
            {"baz": "http://example.com/baz.md"}
        ]
    }';

    protected $jsonMissingContent = '{
        "title": "Example Title",
        "content": []
    }';

    protected $jsonContentIndex = '{
        "title": "Example Title",
        "content": [
            {"index": "index.md"}
        ]
    }';

    protected $jsonContentNotArray = '{
        "title": "Example Title",
        "content": "not an array"
    }';

    protected $jsonContentItemNotStringOrObject = '{
        "title": "Example Title",
        "content": [
            ["neither string", "nor object"]
        ]
    }';

    protected $jsonContentConvenience = '{
        "title": "Example Title",
        "content": [
            "foo.md",
            "bar/bookdown.json",
            "http://example.com/baz.md",
            "http://example.dom/dib/bookdown.json"
        ]
    }';

    protected $jsonContentSameResolvedName = '{
        "title": "Example Title",
        "content": [
            "http://test1.example.dom/master/bookdown.json",
            "http://test2.example.dom/master/bookdown.json"
        ]
    }';

    protected $jsonReusedContentName = '{
        "title": "Example Title",
        "content": [
            "foo.md",
            "foo/bookdown.json"
        ]
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

    public function testContentNotArray()
    {
        $this->setExpectedException(
            'Bookdown\Bookdown\Exception',
            "Content must be an array in '/path/to/bookdown.json'."
        );
        $config = $this->newIndexConfig('/path/to/bookdown.json', $this->jsonContentNotArray);
    }

    public function testContentItemNotStringOrObject()
    {
        $this->setExpectedException(
            'Bookdown\Bookdown\Exception',
            "Content origin must be object or string in '/path/to/bookdown.json'."
        );
        $config = $this->newIndexConfig('/path/to/bookdown.json', $this->jsonContentItemNotStringOrObject);
    }

    public function testContentIndex()
    {
        $this->setExpectedException(
            'Bookdown\Bookdown\Exception',
            "Disallowed 'index' content name in '/path/to/bookdown.json'."
        );
        $config = $this->newIndexConfig('/path/to/bookdown.json', $this->jsonContentIndex);
    }

    public function testDuplicateName()
    {
        $this->setExpectedException(
            'Bookdown\Bookdown\Exception',
            "Content name 'master' already set in '/path/to/bookdown.json'."
        );
        $config = $this->newIndexConfig('/path/to/bookdown.json', $this->jsonContentSameResolvedName);
    }


    public function testValidLocal()
    {
        $config = $this->newIndexConfig('/path/to/bookdown.json', $this->jsonValidLocal);

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

    public function testValidRemote()
    {
        $config = $this->newIndexConfig(
            'http://example.net/path/to/bookdown.json',
            $this->jsonValidRemote
        );

        $this->assertSame('http://example.net/path/to/bookdown.json', $config->getFile());

        $expect = array(
            'zim' => 'http://example.net/path/to/zim.md',
            'dib' => 'http://example.net/path/to/dib.md',
            'gir' => 'http://example.com/gir.md',
        );
        $this->assertSame($expect, $config->getContent());
    }

    public function testContentConvenience()
    {
        $config = $this->newIndexConfig(
            '/path/to/bookdown.json',
            $this->jsonContentConvenience
        );

        $this->assertSame('/path/to/bookdown.json', $config->getFile());

        $expect = array(
            'foo' => '/path/to/foo.md',
            'bar' => '/path/to/bar/bookdown.json',
            'baz' => 'http://example.com/baz.md',
            'dib' => 'http://example.dom/dib/bookdown.json',
        );

        $this->assertSame($expect, $config->getContent());
    }

    public function testReusedContentName()
    {
        $this->setExpectedException(
            'Bookdown\Bookdown\Exception',
            "Content name 'foo' already set in '/path/to/bookdown.json'."
        );
        $config = $this->newIndexConfig(
            '/path/to/bookdown.json',
            $this->jsonReusedContentName
        );
    }

    public function testInvalidRemote()
    {
        $this->setExpectedException(
            'Bookdown\Bookdown\Exception',
            "Cannot handle absolute content path '/bar.md' in remote 'http://example.net/path/to/bookdown.json'."
        );
        $config = $this->newIndexConfig(
            'http://example.net/path/to/bookdown.json',
            $this->jsonValidLocal
        );
    }
}
