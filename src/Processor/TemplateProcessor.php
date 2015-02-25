<?php
namespace Bookdown\Bookdown\Processor;

use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Template\TemplateInterface;

class TemplateProcessor
{
    public function __construct(TemplateInterface $template)
    {
        $this->template = $template;
    }

    public function __invoke(Page $page)
    {
        $html = $this->template->render($page);
        file_put_contents($page->getTargetFile(), $html);
    }
}
