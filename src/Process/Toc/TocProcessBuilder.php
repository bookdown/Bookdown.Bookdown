<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Process\Toc;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

/**
 *
 *
 *
 * @package bookdown/bookdown
 *
 */
class TocProcessBuilder implements ProcessBuilderInterface
{
    /**
     *
     * Returns a new TocProcess object.
     *
     * @param RootConfig $config The root-level config object.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param Fsio $fsio A filesystem I/O object.
     *
     * @return TocProcess
     *
     */
    public function newInstance(RootConfig $config, LoggerInterface $logger, Fsio $fsio)
    {
        return new TocProcess($logger);
    }
}
