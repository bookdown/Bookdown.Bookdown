<?php
namespace Bookdown\Bookdown;

class CommandTest extends \PHPUnit_Framework_TestCase
{
    protected $container;
    protected $fixture;
    protected $fsio;
    protected $stdout;
    protected $stderr;

    protected function setUp()
    {
        $this->stdout = fopen('php://memory', 'a+');
        $this->stderr = fopen('php://memory', 'a+');

        $this->container = new Container(
            $this->stdout,
            $this->stderr,
            'Bookdown\Bookdown\FakeFsio'
        );

        $this->fsio = $this->container->getFsio();
        $this->fixture = new BookFixture($this->fsio);

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
        rewind($this->stderr);
        $string = '';
        while ($chars = fread($this->stderr, 8192)) {
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
