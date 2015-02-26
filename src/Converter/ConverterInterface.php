<?php
namespace Bookdown\Bookdown\Converter;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Content\Page;

interface ConverterInterface
{
    public function convert(Page $page, Stdio $stdio);
}
