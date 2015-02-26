<?php
namespace Bookdown\Bookdown\Template;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Content\Page;

interface TemplateInterface
{
    public function __invoke(Page $page, Stdio $stdio);
}
