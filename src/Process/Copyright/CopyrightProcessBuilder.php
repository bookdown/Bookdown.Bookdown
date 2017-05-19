<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Process\Copyright;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

/**
 *
 * Builds a CopyrightProcess object.
 *
 * @package bookdown/bookdown
 *
 */
class CopyrightProcessBuilder implements ProcessBuilderInterface
{
    /**
     *
     * Returns a new CopyrightProcess object.
     *
     * @param RootConfig $config The root-level config object.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param Fsio $fsio A filesystem I/O object.
     *
     * @return CopyrightProcess
     *
     */
    public function newInstance(RootConfig $config, LoggerInterface $logger, Fsio $fsio)
    {
        return new CopyrightProcess(
            $logger,
            $fsio,
            $config
        );
    }
}
