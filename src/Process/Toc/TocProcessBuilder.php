<?php
namespace Bookdown\Bookdown\Process\Toc;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

class TocProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, Stdio $stdio, Fsio $fsio)
    {
        return new TocProcess($stdio);
    }
}
