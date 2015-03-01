<?php
namespace Bookdown\Bookdown;

use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Bookdown\Bookdown\Service\Service;
use Exception as AnyException;

class Command
{
    protected $context;
    protected $stdio;
    protected $fsio;
    protected $service;

    public function __construct(
        Context $context,
        Stdio $stdio,
        Fsio $fsio,
        Service $service
    ) {
        $this->context = $context;
        $this->stdio = $stdio;
        $this->fsio = $fsio;
        $this->service = $service;
    }

    public function __invoke()
    {
        try {
            $rootConfigFile = $this->init();
            $this->service->__invoke($rootConfigFile);
            $this->reportTime($this->service->getTime());
            return 0;
        } catch (AnyException $e) {
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
                "Please enter the path to a bookdown.json file as the first argument."
            );
        }

        $rootConfigFile = $this->fsio->realpath($file);
        if (! $rootConfigFile) {
            throw new Exception(
                "Could not resolve '{$file}' to a real path."
            );
        }

        $this->started = microtime(true);
        return $rootConfigFile;
    }

    protected function reportTime($time)
    {
        $seconds = trim(sprintf("%10.2f", $time));
        $this->stdio->outln("Completed in {$seconds} seconds.");
    }
}
