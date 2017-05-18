<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Process;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;

/**
 *
 * Interface for building process objects.
 *
 * @package bookdown/bookdown
 *
 */
interface ProcessBuilderInterface
{
    /**
     *
     * Returns a new Process object.
     *
     * @param RootConfig $rootConfig The root-level config object.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param Fsio $fsio A filesystem I/O object.
     *
     * @return ProcessInterface
     *
     */
    public function newInstance(RootConfig $config, LoggerInterface $logger, Fsio $fsio);
}
