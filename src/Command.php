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
    protected $builder;

    public function __construct(
        Context $context,
        Stdio $stdio,
        Builder $builder
    ) {
        $this->context = $context;
        $this->stdio = $stdio;
        $this->builder = $builder;
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
        $collector = $this->builder->newCollector();
        $this->root = $collector($this->origin);
        $this->config = $this->root->getConfig();
    }

    protected function processPages()
    {
        $processor = $this->builder->newProcessor($this->config);
        $processor($this->root);
    }

    protected function reportTime()
    {
        $seconds = trim(sprintf("%10.2f", microtime(true) - $this->start));
        $this->stdio->outln("Completed in {$seconds} seconds.");
    }
}
