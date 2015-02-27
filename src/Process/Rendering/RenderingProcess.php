<?php
namespace Bookdown\Bookdown\Process\Rendering;

use Aura\Cli\Stdio;
use Aura\View\View;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;

class RenderingProcess implements ProcessInterface
{
    protected $stdio;
    protected $fsio;
    protected $view;

    public function __construct(
        Stdio $stdio,
        Fsio $fsio,
        View $view
    ) {
        $this->stdio = $stdio;
        $this->fsio = $fsio;
        $this->view = $view;
    }

    public function __invoke(Page $page)
    {
        $file = $page->getTarget();
        $this->stdio->outln("    Rendering {$file}");
        $this->view->page = $page;
        $this->view->html = $this->fsio->get($page->getTarget());
        $result = $this->view->__invoke();
        $this->fsio->put($file, $result);
    }
}
