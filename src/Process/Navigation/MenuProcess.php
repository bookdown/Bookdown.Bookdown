<?php
namespace Bookdown\Bookdown\Process\Navigation;

use Bookdown\Bookdown\Config\RootConfig;
use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;

class MenuProcess implements ProcessInterface
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
     * Menu entries
     *
     * @var []
     */
    protected $menuEntries = [];

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

    public function __invoke(Page $root)
    {
        if (true === $this->processExecuted) {
            $root->setMenuEntries($this->menuEntries);
            return;
        }
        $page = $root;

        while ($page) {
            $this->menuEntries[] = $page;
            $page = $page->getNext();
        }
        $root->setMenuEntries($this->menuEntries);
        $this->processExecuted = true;
    }
}
