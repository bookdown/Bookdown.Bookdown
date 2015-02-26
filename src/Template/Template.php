<?php
namespace Bookdown\Bookdown\Template;

use Aura\View\View;
use Bookdown\Bookdown\Content\Page;

class Template implements TemplateInterface
{
    protected $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function render(Page $page)
    {
        $this->view->page = $page;
        return $this->view->__invoke();
    }
}
