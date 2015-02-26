<?php
namespace Bookdown\Bookdown\Process;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Config\RootConfig;

class TocProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, Stdio $stdio)
    {
        return new TocProcess($stdio);
    }
}
