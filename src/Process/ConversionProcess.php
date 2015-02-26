<?php
namespace Bookdown\Bookdown\Process;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Exception;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;
use League\CommonMark\CommonMarkConverter;

class ConversionProcess implements ProcessInterface
{
    protected $fsio;
    protected $commonMarkConverter;

    public function __construct(
        Fsio $fsio,
        CommonMarkConverter $commonMarkConverter
    ) {
        $this->fsio = $fsio;
        $this->commonMarkConverter = $commonMarkConverter;
    }

    public function __invoke(Page $page, Stdio $stdio)
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

        $stdio->outln("Reading origin {$file}");
        return $this->fsio->get($file);
    }

    protected function saveTarget(Page $page, Stdio $stdio, $html)
    {
        $dir = dirname($page->getTarget());
        if (! $this->fsio->isDir($dir)) {
            $stdio->outln("Making directory {$dir}");
            $this->fsio->mkdir($dir);
        }

        $file = $page->getTarget();
        $stdio->outln("Saving target {$file}");
        $this->fsio->put($file, $html);
    }
}
