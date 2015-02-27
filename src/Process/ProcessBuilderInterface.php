<?php
namespace Bookdown\Bookdown\Process;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;

interface ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, Stdio $stdio, Fsio $fsio);
}
