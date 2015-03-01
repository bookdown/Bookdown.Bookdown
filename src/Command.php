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
    protected $service;

    public function __construct(
        Context $context,
        Stdio $stdio,
        Service $service
    ) {
        $this->context = $context;
        $this->stdio = $stdio;
        $this->service = $service;
    }

    public function __invoke()
    {
        try {
            $rootConfigFile = $this->init();
            $this->service->__invoke($rootConfigFile);
            return 0;
        } catch (AnyException $e) {
            $this->stdio->errln($e->getMessage());
            $code = $e->getCode() ? $e->getCode() : 1;
            return $code;
        }
    }

    protected function init()
    {
        $rootConfigFile = $this->context->argv->get(1);
        if (! $rootConfigFile) {
            throw new Exception(
                "Please enter the path to a bookdown.json file as the first argument."
            );
        }
        return $rootConfigFile;
    }
}
