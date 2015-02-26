<?php
namespace Bookdown\Bookdown\Process;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Content\Page;

interface ProcessInterface
{
    public function __invoke(Page $page, Stdio $stdio);
}
