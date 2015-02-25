<?php
namespace Bookdown\Bookdown\Converter;

use Bookdown\Bookdown\Content\Page;

interface ConverterInterface
{
    public function convert(Page $page);
}
