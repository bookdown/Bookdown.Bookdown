<?php
namespace Bookdown\Bookdown;

use Bookdown\Bookdown\Config;
use Bookdown\Bookdown\Content;
use Bookdown\Bookdown\Converter;
use Bookdown\Bookdown\Template;
use Bookdown\Bookdown\Processor;

class Command
{
    protected $origin;
    protected $root;

    public function __invoke($server)
    {
        $this->init($server);
        $this->collectPages();
        $this->processPages();
    }

    protected function init($server)
    {
        if (! isset($server['argv'][1])) {
            throw new Exception(
                "Please enter an origin bookdown.json file as the first argument."
            );
        }

        $file = $server['argv'][1];
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
            new Content\PageBuilder(
                new Config\ConfigBuilder()
            )
        );
        $this->root = $pageCollector($this->origin);
    }

    protected function processPages()
    {
        $processor = $this->newProcessor();
        $processor($this->root);
    }

    protected function newProcessor()
    {
        return new Processor\Processor(array(
            new Processor\ConverterProcessor($this->newConverter()),
            new Processor\HeadingsProcessor(new Content\HeadingFactory()),
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
