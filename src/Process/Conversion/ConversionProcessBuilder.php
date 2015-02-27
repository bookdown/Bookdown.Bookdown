<?php
namespace Bookdown\Bookdown\Process\Conversion;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use League\CommonMark\CommonMarkConverter;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

class ConversionProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, Stdio $stdio, Fsio $fsio)
    {
        return new ConversionProcess(
            $stdio,
            $fsio,
            $this->newCommonMarkConverter($config)
        );
    }

    protected function newCommonMarkConverter(RootConfig $config)
    {
        return new CommonMarkConverter();
    }
}
