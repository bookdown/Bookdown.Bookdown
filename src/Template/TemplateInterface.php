<?php
namespace Bookdown\Bookdown\Template;

use Bookdown\Bookdown\Content\Page;

interface TemplateInterface
{
    public function render(Page $page);
}
