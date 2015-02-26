<?php
namespace Bookdown\Bookdown\Processor;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Template\TemplateInterface;

class TemplateProcessor
{
    public function __construct(TemplateInterface $template)
    {
        $this->template = $template;
    }

    public function __invoke(Page $page, Stdio $stdio)
    {
        $stdio->outln("Processing template for {$page->getTargetFile()}");
        $html = $this->template->render($page, $stdio);
        file_put_contents($page->getTargetFile(), $html);
    }
}
