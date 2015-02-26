<?php
namespace Bookdown\Bookdown\Converter;

use Aura\Cli\Stdio;
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

    public function convert(Page $page, Stdio $stdio)
    {
        $text = $this->readOrigin($page, $stdio);
        $html = $this->commonMarkConverter->convertToHtml($text);
        $this->saveTarget($page, $stdio, $html);
    }

    protected function readOrigin(Page $page, Stdio $stdio)
    {
        $file = $page->getOrigin();
        if (! $file) {
            $stdio->outln("No origin for {$page->getTarget()}");
            return;
        }

        $stdio->outln("Reading {$file}");
        $level = error_reporting(0);
        $result = file_get_contents($file);
        error_reporting($level);

        if ($result !== false) {
            return $result;
        }

        $error = error_get_last();
        throw new Exception($error['message']);
    }

    protected function saveTarget(Page $page, Stdio $stdio, $html)
    {
        $this->mkdir($page, $stdio);

        $file = $page->getTarget();
        $stdio->outln("Saving {$file}");

        $level = error_reporting(0);
        $result = file_put_contents($file, $html);
        error_reporting($level);

        if ($result !== false) {
            return;
        }

        $error = error_get_last();
        throw new Exception($error['message']);
    }

    protected function mkdir(Page $page, Stdio $stdio)
    {
        $dir = dirname($page->getTarget());
        if (is_dir($dir)) {
            return;
        }

        $stdio->outln("Making directory {$dir}");

        $level = error_reporting(0);
        $result = mkdir($dir, 0777, true);
        error_reporting($level);

        if ($result !== false) {
            return;
        }

        $error = error_get_last();
        throw new Exception($error['message']);
    }
}
