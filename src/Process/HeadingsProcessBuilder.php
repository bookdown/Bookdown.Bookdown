<?php
namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Content\HeadingFactory;
use Bookdown\Bookdown\Fsio;

class HeadingsProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config)
    {
        return new HeadingsProcess(
            $this->newFsio(),
            $this->newHeadingFactory()
        );
    }

    protected function newFsio()
    {
        return new Fsio();
    }

    protected function newHeadingFactory()
    {
        return new HeadingFactory();
    }
}
