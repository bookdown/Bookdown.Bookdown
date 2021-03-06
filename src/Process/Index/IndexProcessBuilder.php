<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Process\Index;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

/**
 *
 * Builds an IndexProcess object.
 *
 * @package bookdown/bookdown
 *
 */
class IndexProcessBuilder implements ProcessBuilderInterface
{
    /**
     *
     * Returns a new IndexProcess object.
     *
     * @param RootConfig $config The root-level config object.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param Fsio $fsio A filesystem I/O object.
     *
     * @return IndexProcess
     *
     */
    public function newInstance(RootConfig $config, LoggerInterface $logger, Fsio $fsio)
    {
        return new IndexProcess(
            $logger,
            $fsio,
            $config
        );
    }
}
