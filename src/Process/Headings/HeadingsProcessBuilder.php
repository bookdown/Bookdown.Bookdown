<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Process\Headings;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Content\HeadingFactory;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

/**
 *
 *
 *
 * @package bookdown/bookdown
 *
 */
class HeadingsProcessBuilder implements ProcessBuilderInterface
{
    /**
     *
     * Returns a new HeadingsProcess object.
     *
     * @param RootConfig $rootConfig The root-level config object.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param Fsio $fsio A filesystem I/O object.
     *
     * @return HeadingsProcess
     *
     */
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
