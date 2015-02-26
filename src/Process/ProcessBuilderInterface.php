<?php
namespace Bookdown\Bookdown\Process;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Config\RootConfig;

interface ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, Stdio $stdio);
}
