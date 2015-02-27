<?php
namespace Bookdown\Bookdown;

use Aura\Cli\CliFactory;
use Bookdown\Bookdown\Config\RootConfig;

class Builder
{
    protected $stdout;
    protected $stderr;
    protected $stdio;
    protected $cliFactory;
    protected $fsioClass;
    protected $fsio;

    public function __construct(
        $stdout = 'php://stdout',
        $stderr = 'php://stderr',
        $fsioClass = 'Bookdown\Bookdown\Fsio'
    ) {
        $this->stdout = $stdout;
        $this->stderr = $stderr;
        $this->fsioClass = $fsioClass;
    }

    public function newCommand($globals)
    {
        return new Command(
            $this->getCliFactory()->newContext($globals),
            $this->getStdio(),
            $this
        );
    }

    public function newCollector()
    {
        return new Collector(
            $this->getStdio(),
            new Config\ConfigBuilder($this->getFsio()),
            new Content\PageFactory()
        );
    }

    public function newProcessor(RootConfig $config)
    {
        return new Processor(
            $this->getStdio(),
            array(
                $this->newProcess($config, 'Conversion'),
                $this->newProcess($config, 'Headings'),
                $this->newProcess($config, 'Toc'),
                $this->newProcess($config, 'Rendering'),
            )
        );
    }

    public function newProcess(RootConfig $config, $name)
    {
        $method = "get{$name}Process";
        $class = $config->$method();

        $implemented = is_subclass_of(
            $class,
            'Bookdown\Bookdown\Process\ProcessBuilderInterface'
        );
        if (! $implemented) {
            throw new Exception(
                "'{$class}' does not implement ProcessBuilderInterface"
            );
        }

        $builder = new $class();
        return $builder->newInstance($config, $this->getStdio(), $this->getFsio());
    }

    public function getCliFactory()
    {
        if (! $this->cliFactory) {
            $this->cliFactory = new CliFactory();
        }
        return $this->cliFactory;
    }

    public function getStdio()
    {
        if (! $this->stdio) {
            $this->stdio = $this->getCliFactory()->newStdio(
                'php://stdin',
                $this->stdout,
                $this->stderr
            );
        }
        return $this->stdio;
    }

    public function getFsio()
    {
        if (! $this->fsio) {
            $class = $this->fsioClass;
            $this->fsio = new $class();
        }
        return $this->fsio;
    }
}
