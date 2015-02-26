<?php
namespace Bookdown\Bookdown\Process;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use League\CommonMark\CommonMarkConverter;

class ConversionProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, Stdio $stdio)
    {
        return new ConversionProcess(
            $stdio,
            $this->newFsio(),
            $this->newCommonMarkConverter()
        );
    }

    protected function newCommonMarkConverter()
    {
        return new CommonMarkConverter();
    }

    protected function newFsio()
    {
        return new Fsio();
    }
}
