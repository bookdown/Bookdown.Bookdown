<?php
namespace Bookdown\Bookdown\Processor;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Converter\ConverterInterface;

class ConverterProcessor
{
    protected $converter;

    public function __construct(ConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    public function __invoke(Page $page, Stdio $stdio)
    {
        $file = $page->getTarget();
        $html = $this->converter->convert($page, $stdio);
    }
}
