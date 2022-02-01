<?php
namespace Bookdown\Bookdown;

use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class FsioTest extends TestCase
{
    protected $fsio;
    protected $base;

    protected function set_up()
    {
        $this->fsio = new Fsio();
        $this->base = __DIR__ . DIRECTORY_SEPARATOR . 'tmp';
    }

    public function testIsDir()
    {
        $this->assertTrue($this->fsio->isDir(__DIR__));
    }

    protected function getPath($path)
    {
        return $this->base . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $path);
    }

    public function testMkdir()
    {
        $dir = $this->getPath('fakedir');
        if ($this->fsio->isDir($dir)) {
            rmdir($dir);
        }

        $this->assertFalse($this->fsio->isDir($dir));
        $this->fsio->mkdir($dir);
        $this->assertTrue($this->fsio->isDir($dir));
        rmdir($dir);
        $this->assertFalse($this->fsio->isDir($dir));

        $this->expectException(
            'Bookdown\Bookdown\Exception',
            'mkdir(): File exists'
        );
        $this->fsio->mkdir(__DIR__);
    }

    public function testGet()
    {
        $text = $this->fsio->get(__FILE__);
        $this->assertSame('<?php', substr($text, 0, 5));

        $this->expectException(
            'Bookdown\Bookdown\Exception',
            'No such file or directory'
        );
        $this->fsio->get($this->getPath('no-such-file'));
    }

    public function testPut()
    {
        $file = $this->getPath('fakefile');
        if (file_exists($file)) {
            unlink($file);
        }

        $expect = 'fake text';
        $this->fsio->put($file, $expect);
        $actual = $this->fsio->get($file);
        $this->assertSame($expect, $actual);
        unlink($file);

        $file = $this->getPath('no-such-directory/fakefile');
        $this->expectException(
            'Bookdown\Bookdown\Exception',
            'No such file or directory'
        );
        $this->fsio->put($file, $expect);
    }
}
