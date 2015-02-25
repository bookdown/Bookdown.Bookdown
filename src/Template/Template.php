<?php
namespace Bookdown\Bookdown\Template;

use Aura\View\View;

class Template implements TemplateInterface
{
    protected $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function setPage($page)
    {
        $this->view->page = $page;
        $this->view->html = file_get_contents($page->getTargetFile());
    }

    public function render()
    {
        return $this->view->__invoke();
    }
}
