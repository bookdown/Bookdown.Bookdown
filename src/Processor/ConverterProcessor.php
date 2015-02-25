<?php
namespace Bookdown\Bookdown\Processor;

use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Converter\ConverterInterface;

class ConverterProcessor
{
    protected $converter;

    public function __construct(ConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    public function __invoke(Page $page)
    {
        $html = $this->converter->convert($page);

        $file = $page->getTargetFile();
        $dir = dirname($file);
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($file, $html);
    }
}
