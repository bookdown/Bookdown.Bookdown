<?php
namespace Bookdown\Bookdown\Process\Rendering;

use Psr\Log\LoggerInterface;
use Aura\View\View;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;

class RenderingProcess implements ProcessInterface
{
    protected $logger;
    protected $fsio;
    protected $view;

    public function __construct(
        LoggerInterface $logger,
        Fsio $fsio,
        View $view
    ) {
        $this->logger = $logger;
        $this->fsio = $fsio;
        $this->view = $view;
    }

    public function __invoke(Page $page)
    {
        $file = $page->getTarget();
        $this->logger->info("    Rendering {$file}");
        $this->view->page = $page;
        $this->view->html = '<div id="section-main">'
            .$this->fsio->get($page->getTarget())
            .'</div>';
        $result = $this->view->__invoke();
        $this->fsio->put($file, $result);
    }
}
