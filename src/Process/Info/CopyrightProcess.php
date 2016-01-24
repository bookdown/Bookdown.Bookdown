<?php
namespace Bookdown\Bookdown\Process\Info;

use Bookdown\Bookdown\Config\RootConfig;
use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;

class CopyrightProcess implements ProcessInterface
{
    /**
     * @var RootConfig
     */
    protected $config;

    /**
     * @var Fsio
     */
    protected $fsio;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Process already executed
     *
     * @var bool
     */
    protected $processExecuted = false;

    public function __construct(
        LoggerInterface $logger,
        Fsio $fsio,
        RootConfig $config
    ) {
        $this->logger = $logger;
        $this->fsio = $fsio;
        $this->config = $config;
    }

    public function __invoke(Page $page)
    {
        $page->setCopyright($this->config->getCopyright());
    }
}
