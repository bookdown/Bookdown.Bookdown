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
            $this->newConversion(),
            new Process\HeadingsProcess(
                new Fsio,
                new Content\HeadingFactory()
            ),
            new Process\TocProcess(),
            $this->newRendering(),
        ));
    }

    protected function newRendering()
    {
        $config = $this->root->getConfig();

        $class = $config->getRenderingBuilder();
        $factory = new $class();
        if (! $factory instanceof Process\ProcessBuilderInterface) {
            throw new Exception(
                "'{$class}' does not implement ProcessBuilderInterface."
            );
        }

        return $factory->newInstance($config);
    }

    protected function newConversion()
    {
        $config = $this->root->getConfig();

        $class = $config->getConversionBuilder();
        $factory = new $class();
        if (! $factory instanceof Process\ProcessBuilderInterface) {
            throw new Exception(
                "'{$class}' does not implement ProcessBuilderInterface."
            );
        }

        return $factory->newInstance($config);
    }

    protected function reportTime()
    {
        $seconds = trim(sprintf("%10.2f", microtime(true) - $this->start));
        $this->stdio->outln("Completed in {$seconds} seconds.");
    }
}
