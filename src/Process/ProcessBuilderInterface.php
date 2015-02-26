<?php
namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\Config\RootConfig;

interface ProcessBuilderInterface
{
    public function newInstance(RootConfig $config);
}
