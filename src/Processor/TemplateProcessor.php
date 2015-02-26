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
        $this->template->__invoke($page, $stdio);
    }
}
