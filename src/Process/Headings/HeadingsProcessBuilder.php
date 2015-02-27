<?php
namespace Bookdown\Bookdown\Process\Headings;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Content\HeadingFactory;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

class HeadingsProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, Stdio $stdio, Fsio $fsio)
    {
        return new HeadingsProcess(
            $stdio,
            $fsio,
            $this->newHeadingFactory()
        );
    }

    protected function newHeadingFactory()
    {
        return new HeadingFactory();
    }
}
