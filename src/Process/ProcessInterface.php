<?php
namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\Content\Page;

interface ProcessInterface
{
    public function __invoke(Page $page);
}
