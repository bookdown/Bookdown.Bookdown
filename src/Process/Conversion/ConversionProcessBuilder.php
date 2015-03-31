<?php
namespace Bookdown\Bookdown\Process\Conversion;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use League\CommonMark\CommonMarkConverter;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

class ConversionProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, LoggerInterface $logger, Fsio $fsio)
    {
        return new ConversionProcess(
            $logger,
            $fsio,
            $this->newCommonMarkConverter($config)
        );
    }

    protected function newCommonMarkConverter(RootConfig $config)
    {
        return new CommonMarkConverter();
    }
}
