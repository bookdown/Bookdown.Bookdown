<?php
namespace Bookdown\Bookdown\Process\Toc;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

class TocProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, LoggerInterface $logger, Fsio $fsio)
    {
        return new TocProcess($logger);
    }
}
