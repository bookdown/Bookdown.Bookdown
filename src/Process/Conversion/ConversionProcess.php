<?php
namespace Bookdown\Bookdown\Process\Conversion;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Exception;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;
use League\CommonMark\CommonMarkConverter;

class ConversionProcess implements ProcessInterface
{
    protected $page;
    protected $stdio;
    protected $fsio;
    protected $commonMarkConverter;

    public function __construct(
        Stdio $stdio,
        Fsio $fsio,
        CommonMarkConverter $commonMarkConverter
    ) {
        $this->stdio = $stdio;
        $this->fsio = $fsio;
        $this->commonMarkConverter = $commonMarkConverter;
    }

    public function __invoke(Page $page)
    {
        $this->page = $page;
        $text = $this->readOrigin();
        $html = $this->commonMarkConverter->convertToHtml($text);
        $this->saveTarget($html);
    }

    protected function readOrigin()
    {
        $file = $this->page->getOrigin();
        if (! $file) {
            $this->stdio->outln("No origin for {$this->page->getTarget()}");
            return;
        }

        $this->stdio->outln("Reading origin {$file}");
        return $this->fsio->get($file);
    }

    protected function saveTarget($html)
    {
        $file = $this->page->getTarget();
        $dir = dirname($file);
        if (! $this->fsio->isDir($dir)) {
            $this->stdio->outln("Making directory {$dir}");
            $this->fsio->mkdir($dir);
        }

        $this->stdio->outln("Saving target {$file}");
        $this->fsio->put($file, $html);
    }
}
