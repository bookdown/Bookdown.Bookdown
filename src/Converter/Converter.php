<?php
namespace Bookdown\Bookdown\Converter;

use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Exception;
use League\CommonMark\CommonMarkConverter;

class Converter implements ConverterInterface
{
    protected $commonMarkConverter;

    public function __construct(CommonMarkConverter $commonMarkConverter)
    {
        $this->commonMarkConverter = $commonMarkConverter;
    }

    public function convert(Page $page)
    {
        $text = $this->readOriginFile($page);
        return $this->commonMarkConverter->convertToHtml($text);
    }

    protected function readOriginFile(Page $page)
    {
        $file = $page->getOrigin();
        if (! $file) {
            return;
        }

        $level = error_reporting(0);
        $text = file_get_contents($file);
        error_reporting($level);

        if ($text !== false) {
            return $text;
        }

        $error = error_get_last();
        throw new Exception($error['message']);
    }
}
