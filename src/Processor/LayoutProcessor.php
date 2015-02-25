<?php
namespace Bookdown\Bookdown\Processor;

use Aura\View\View;
use Bookdown\Bookdown\Content\Page;

class LayoutProcessor
{
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function __invoke(Page $page)
    {
        $file = $page->getTargetFile();
        $this->view->page = $page;
        $this->view->html = file_get_contents($file);
        $html = $this->view->__invoke();
        file_put_contents($file, $html);
    }
}
