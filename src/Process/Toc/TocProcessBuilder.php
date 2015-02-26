<?php
namespace Bookdown\Bookdown\Process\Toc;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

class TocProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, Stdio $stdio)
    {
        return new TocProcess($stdio);
    }
}
