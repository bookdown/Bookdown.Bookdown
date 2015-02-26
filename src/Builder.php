<?php
namespace Bookdown\Bookdown;

use Aura\Cli\CliFactory;
use Bookdown\Bookdown\Config\RootConfig;

class Builder
{
    protected $stdout;
    protected $stderr;
    protected $stdio;

    public function __construct(
        $stdout = 'php://stdout',
        $stderr = 'php://stderr'
    ) {
        $this->stdout = $stdout;
        $this->stderr = $stderr;
    }

    public function newCommand($globals)
    {
        $cliFactory = new CliFactory();
        $context = $cliFactory->newContext($globals);
        $stdio = $this->getStdio();
        return new Command($context, $stdio, $this);
    }

    public function getStdio()
    {
        if (! $this->stdio) {
            $cliFactory = new CliFactory();
            $this->stdio = $cliFactory->newStdio(
                'php://stdin',
                $this->stdout,
                $this->stderr
            );
        }
        return $this->stdio;
    }

    public function newCollector()
    {
        return new Collector(
            $this->getStdio(),
            new Content\PageBuilder(
                new Config\ConfigBuilder(
                    new Fsio
                )
            )
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
        return $builder->newInstance($config, $this->getStdio());
    }
}
