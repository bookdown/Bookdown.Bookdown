<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Process\CopyImage;

use Bookdown\Bookdown\Content\ImageFactory;
use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

/**
 *
 * Builds a CopyImageProcess object.
 *
 * @package bookdown/bookdown
 *
 */
class CopyImageProcessBuilder implements ProcessBuilderInterface
{
    /**
     *
     * Returns a new CopyImageProcess object.
     *
     * @param RootConfig $config The root-level config object.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param Fsio $fsio A filesystem I/O object.
     *
     * @return CopyImageProcess
     *
     */
    public function newInstance(RootConfig $config, LoggerInterface $logger, Fsio $fsio)
    {
        return new CopyImageProcess(
            $logger,
            $fsio,
            $config
        );
    }
}
