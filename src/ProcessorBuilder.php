<?php
namespace Bookdown\Bookdown;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;

class ProcessorBuilder
{
    public function __construct(Stdio $stdio, Fsio $fsio)
    {
        $this->stdio = $stdio;
        $this->fsio = $fsio;
    }

    public function newProcessor(RootConfig $config)
    {
        return new Processor(
            $this->stdio,
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
        return $builder->newInstance($config, $this->stdio, $this->fsio);
    }
}