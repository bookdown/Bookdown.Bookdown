<?php
namespace Bookdown\Bookdown;

use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Exception as AnyException;

class Command
{
    protected $origin;
    protected $root;
    protected $stdio;
    protected $context;
    protected $start;

    public function __construct(
        Context $context,
        Stdio $stdio
    ) {
        $this->context = $context;
        $this->stdio = $stdio;
    }

    public function __invoke()
    {
        try {
            $this->init();
            $this->collectPages();
            $this->processPages();
            $this->reportTime();
            return 0;
        } catch (AnyException $e) {
            $this->stdio->errln((string) $e);
            $this->stdio->errln($e->getMessage());
            $code = $e->getCode() ? $e->getCode() : 1;
            return $code;
        }
    }

    protected function init()
    {
        $file = $this->context->argv->get(1);
        if (! $file) {
            throw new Exception(
                "Please enter an origin bookdown.json file as the first argument."
            );
        }

        $this->origin = realpath($file);
        if (! $this->origin) {
            throw new Exception(
                "Could not resolve '{$file}' to a real path."
            );
        }

        $this->start = microtime(true);
    }

    protected function collectPages()
    {
        $collector = $this->newCollector();
        $this->root = $collector($this->origin);
    }

    protected function newCollector()
    {
        return new Collector(
            $this->stdio,
            new Content\PageBuilder(
                new Config\ConfigBuilder(
                    new Fsio
                )
            )
        );
    }

    protected function processPages()
    {
        $processor = $this->newProcessor();
        $processor($this->root, $this->stdio);
    }

    protected function newProcessor()
    {
        return new Processor(array(
            $this->newProcess('Conversion'),
            $this->newProcess('Headings'),
            $this->newProcess('Toc'),
            $this->newProcess('Rendering'),
        ));
    }

    protected function newProcess($name)
    {
        $config = $this->root->getConfig();
        $method = "get{$name}Process";
        $class = $config->$method();
        $implemented = is_subclass_of(
            $class,
            'Bookdown\Bookdown\Process\ProcessBuilderInterface'
        );
        if (! $implemented) {
            throw new Exception(
                "'{$class}' does not implement ProcessBuilderInterface."
            );
        }
        $builder = new $class();
        return $builder->newInstance($config);
    }

    protected function reportTime()
    {
        $seconds = trim(sprintf("%10.2f", microtime(true) - $this->start));
        $this->stdio->outln("Completed in {$seconds} seconds.");
    }
}
