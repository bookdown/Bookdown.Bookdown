<?php
namespace Bookdown\Bookdown\Process;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;

interface ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, LoggerInterface $logger, Fsio $fsio);
}
