<?php
namespace Bookdown\Bookdown\Converter;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Content\Page;

interface ConverterInterface
{
    public function __invoke(Page $page, Stdio $stdio);
}
