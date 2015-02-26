<?php
namespace Bookdown\Bookdown\Process;

use Aura\Cli\Stdio;
use Aura\View\View;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;

class Rendering implements ProcessInterface
{
    protected $fsio;
    protected $view;

    public function __construct(
        Fsio $fsio,
        View $view
    ) {
        $this->fsio = $fsio;
        $this->view = $view;
    }

    public function __invoke(Page $page, Stdio $stdio)
    {
        $file = $page->getTarget();
        $stdio->outln("Rendering template for {$file}");
        $this->view->page = $page;
        $html = $this->view->__invoke();
        $this->fsio->put($file, $html);
    }
}
