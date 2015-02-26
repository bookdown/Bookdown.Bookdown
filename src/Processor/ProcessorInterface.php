<?php
namespace Bookdown\Bookdown\Processor;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Content\Page;

interface ProcessorInterface
{
    public function __invoke(Page $page, Stdio $stdio);
}
