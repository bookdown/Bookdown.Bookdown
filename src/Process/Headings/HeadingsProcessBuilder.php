<?php
namespace Bookdown\Bookdown\Process\Headings;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Content\HeadingFactory;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

class HeadingsProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, LoggerInterface $logger, Fsio $fsio)
    {
        return new HeadingsProcess(
            $logger,
            $fsio,
            $this->newHeadingFactory(),
            $config->getNumbering()
        );
    }

    protected function newHeadingFactory()
    {
        return new HeadingFactory();
    }
}
