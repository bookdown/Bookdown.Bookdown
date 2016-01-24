<?php
namespace Bookdown\Bookdown\Process\Navigation;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

class MenuProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, LoggerInterface $logger, Fsio $fsio)
    {
        return new MenuProcess(
            $logger,
            $fsio,
            $config
        );
    }
}
