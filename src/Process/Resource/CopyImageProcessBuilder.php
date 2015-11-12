<?php
namespace Bookdown\Bookdown\Process\Resource;

use Bookdown\Bookdown\Content\ImageFactory;
use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

class CopyImageProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, LoggerInterface $logger, Fsio $fsio)
    {
        return new CopyImageProcess(
            $logger,
            $fsio,
            $config
        );
    }
}
