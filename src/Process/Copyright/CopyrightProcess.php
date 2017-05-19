<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Process\Copyright;

use Bookdown\Bookdown\Config\RootConfig;
use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;

/**
 *
 * Processes each Page to set its copyright information.
 *
 * @package bookdown/bookdown
 *
 */
class CopyrightProcess implements ProcessInterface
{
    /**
     *
     * The root-level config object.
     *
     * @var RootConfig
     *
     */
    protected $config;

    /**
     *
     * A logger implementation.
     *
     * @var LoggerInterface
     *
     */
    protected $logger;

    /**
     *
     * A filesystem I/O object.
     *
     * @var Fsio
     *
     */
    protected $fsio;

    /**
     *
     * Constructor.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param Fsio $fsio A filesystem I/O object.
     *
     * @param RootConfig The root-level config object.
     *
     */
    public function __construct(
        LoggerInterface $logger,
        Fsio $fsio,
        RootConfig $config
    ) {
        $this->logger = $logger;
        $this->fsio = $fsio;
        $this->config = $config;
    }

    /**
     *
     * Invokes the processor.
     *
     * @param Page $page The Page to process.
     *
     */
    public function __invoke(Page $page)
    {
        $page->setCopyright($this->config->getCopyright());
    }
}
