<?php
namespace Bookdown\Bookdown;

class CommandTest extends \PHPUnit_Framework_TestCase
{
    protected $container;
    protected $fixture;
    protected $fsio;
    protected $stdio;

    protected function setUp()
    {
        $this->container = new Container(
            'php://memory',
            'php://memory',
            'Bookdown\Bookdown\FakeFsio'
        );

        $this->fsio = $this->container->getFsio();
        $this->fixture = new BookFixture($this->fsio);

        $this->stdio = $this->container->getStdio();
    }

    protected function exec(array $argv)
    {
        $command = $this->container->newCommand(array(
            'argv' => $argv
        ));
        return $command();
    }

    protected function assertLastStderr($expect)
    {
        $string = $this->getStderr();
        $lines = explode(PHP_EOL, trim($string));
        $actual = trim(end($lines));
        $this->assertSame($expect, $actual);
    }

    protected function getStderr()
    {
        $handle = $this->stdio->getStderr();
        $handle->rewind();
        $string = '';
        while ($chars = $handle->fread()) {
            $string .= $chars;
        }
        return $string;
    }

    public function testNoConfigFileSpecified()
    {
        $argv = array();
        $exit = $this->exec($argv);
        $this->assertSame(1, $exit);
        $this->assertLastStderr('Please enter the path to a bookdown.json file as the first argument.');
    }

    public function testSuccess()
    {
        $argv = array(
            1 => $this->fixture->rootConfigFile,
        );
        $exit = $this->exec($argv);
        $this->assertSame(0, $exit);
        $this->assertLastStderr('');
    }
}
