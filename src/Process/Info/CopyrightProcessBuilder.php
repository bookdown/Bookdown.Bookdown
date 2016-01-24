<?php
namespace Bookdown\Bookdown\Process\Info;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

class CopyrightProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, LoggerInterface $logger, Fsio $fsio)
    {
        return new CopyrightProcess(
            $logger,
            $fsio,
            $config
        );
    }
}
