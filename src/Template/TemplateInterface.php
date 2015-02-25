<?php
namespace Bookdown\Bookdown\Template;

interface TemplateInterface
{
    public function setPage($page);
    public function render();
}
