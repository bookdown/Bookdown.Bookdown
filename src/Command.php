<?php
namespace Bookdown\Bookdown;

use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Bookdown\Bookdown\Config;
use Bookdown\Bookdown\Content;
use Bookdown\Bookdown\Converter;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Template;
use Bookdown\Bookdown\Processor;

class Command
{
    protected $origin;
    protected $root;
    protected $stdio;
    protected $context;

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
            $time = microtime(true);
            $this->init();
            $this->collectPages();
            $this->processPages();
            $lap = trim(sprintf("%10.2f", microtime(true) - $time));
            $this->stdio->outln("Completed in {$lap} seconds.");
            return 0;
        } catch (Exception $e) {
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
    }

    protected function collectPages()
    {
        $pageCollector = new Content\PageCollector(
            $this->stdio,
            new Content\PageBuilder(
                new Config\ConfigBuilder(
                    new Fsio
                )
            )
        );
        $this->root = $pageCollector($this->origin);
    }

    protected function processPages()
    {
        $processor = $this->newProcessor();
        $processor($this->root, $this->stdio);
    }

    protected function newProcessor()
    {
        return new Processor\Processor(array(
            new Processor\ConverterProcessor($this->newConverter()),
            new Processor\HeadingsProcessor(
                new Fsio,
                new Content\HeadingFactory()
            ),
            new Processor\TocProcessor(),
            new Processor\TemplateProcessor($this->newTemplate()),
        ));
    }

    protected function newTemplate()
    {
        $config = $this->root->getConfig();

        $class = $config->getTemplateBuilder();
        $factory = new $class();
        if (! $factory instanceof Template\TemplateBuilderInterface) {
            throw new Exception(
                "'{$class}' does not implement TemplateBuilderInterface."
            );
        }

        return $factory->newInstance($config);
    }

    protected function newConverter()
    {
        $config = $this->root->getConfig();

        $class = $config->getConverterBuilder();
        $factory = new $class();
        if (! $factory instanceof Converter\ConverterBuilderInterface) {
            throw new Exception(
                "'{$class}' does not implement ConverterBuilderInterface."
            );
        }

        return $factory->newInstance($config);
    }
}
