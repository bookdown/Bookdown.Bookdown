<?php
namespace Bookdown\Bookdown\Processor;

use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Template\TemplateInterface;

class LayoutProcessor
{
    public function __construct(TemplateInterface $template)
    {
        $this->template = $template;
    }

    public function __invoke(Page $page)
    {
        $this->template->setPage($page);
        $html = $this->template->render();
        file_put_contents($page->getTargetFile(), $html);
    }
}
