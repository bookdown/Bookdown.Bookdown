<?php
namespace Bookdown\Content;

use Aura\View\View;

class LayoutProcessor
{
    public function __construct(View $view, array $templates)
    {
        $this->view = $view;

        $registry = $this->view->getViewRegistry();
        foreach ($templates as $name => $file) {
            $registry->set($name, $file);
        }

        // use the first template as the view file
        reset($templates);
        $this->view->setView(key($templates));
    }

    public function __invoke(ContentPage $page)
    {
        $file = $page->getTargetFile();
        $this->view->page = $page;
        $this->view->html = file_get_contents($file);
        $html = $this->view->__invoke();
        file_put_contents($file, $html);
    }
}
