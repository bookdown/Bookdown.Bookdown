<?php
namespace Bookdown\Bookdown\Config;

class RootConfigTest extends \PHPUnit_Framework_TestCase
{
    protected $config;

    protected $maxRootJson = '{
        "title": "Example Title",
        "content": {
            "index": "index.md",
            "foo": "foo.md",
            "bar": "/bar.md",
            "baz": "http://example.com/baz.md"
        },
        "target": "my/target",
        "templates": {
            "master": "master.php"
        },
        "templateName": "master",
        "conversionProcess": "My\\\\Conversion\\\\Builder",
        "headingsProcess": "My\\\\Headings\\\\Builder",
        "tocProcess": "My\\\\Toc\\\\Builder",
        "renderingProcess": "My\\\\Rendering\\\\Builder",
        "extra": "whatever"
    }';

    protected $minRootJson = '{
        "title": "Example Title",
        "content": {
            "index": "index.md",
            "foo": "foo.md",
            "bar": "/bar.md",
            "baz": "http://example.com/baz.md"
        }
    }';

    protected function newRootConfig($file, $data)
    {
        return new RootConfig($file, $data);
    }

    public function testMax()
    {
        $config = $this->newRootConfig('/path/to/bookdown.json', $this->maxRootJson);
        $this->assertBasics($config);

        $this->assertSame('/path/to/my/target/', $config->getTarget());
        $expect = array(
            'master' => 'master.php'
        );
        $this->assertSame($expect, $config->getTemplates());
        $this->assertSame('master', $config->getTemplateName());
        $this->assertSame('My\\Conversion\\Builder', $config->getConversionProcess());
        $this->assertSame('My\\Headings\\Builder', $config->getHeadingsProcess());
        $this->assertSame('My\\Toc\\Builder', $config->getTocProcess());
        $this->assertSame('My\\Rendering\\Builder', $config->getRenderingProcess());
        $this->assertSame('whatever', $config->get('extra'));
        $this->assertSame('none', $config->get('no-such-key', 'none'));
    }

    protected function assertBasics($config)
    {
        $this->assertSame('/path/to/bookdown.json', $config->getFile());
        $this->assertSame('/path/to/', $config->getDir());
        $this->assertSame('Example Title', $config->getTitle());
        $this->assertSame('/path/to/index.md', $config->getIndexOrigin());
        $expect = array(
            'foo' => '/path/to/foo.md',
            'bar' => '/bar.md',
            'baz' => 'http://example.com/baz.md',
        );
        $this->assertSame($expect, $config->getContent());
    }

    public function testMin()
    {
        $config = $this->newRootConfig('/path/to/bookdown.json', $this->minRootJson);
        $this->assertBasics($config);

        $this->assertSame('/path/to/_site/', $config->getTarget());
        $this->assertSame(array(), $config->getTemplates());
        $this->assertSame(null, $config->getTemplateName());
        $this->assertSame(
            'Bookdown\Bookdown\Process\Conversion\ConversionProcessBuilder',
            $config->getConversionProcess()
        );
        $this->assertSame(
            'Bookdown\Bookdown\Process\Headings\HeadingsProcessBuilder',
            $config->getHeadingsProcess()
        );
        $this->assertSame(
            'Bookdown\Bookdown\Process\Toc\TocProcessBuilder',
            $config->getTocProcess()
        );
        $this->assertSame(
            'Bookdown\Bookdown\Process\Rendering\RenderingProcessBuilder',
            $config->getRenderingProcess()
        );
    }
}
