<?php
namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\Config\RootConfig;

class TocProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config)
    {
        return new TocProcess();
    }
}
