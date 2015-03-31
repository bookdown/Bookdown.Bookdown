<?php
namespace Bookdown\Bookdown\Process\Conversion;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Exception;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;
use League\CommonMark\CommonMarkConverter;

class ConversionProcess implements ProcessInterface
{
    protected $page;
    protected $logger;
    protected $fsio;
    protected $commonMarkConverter;

    public function __construct(
        LoggerInterface $logger,
        Fsio $fsio,
        CommonMarkConverter $commonMarkConverter
    ) {
        $this->logger = $logger;
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
            $this->logger->info("    No origin for {$this->page->getTarget()}");
            return;
        }

        $this->logger->info("    Reading origin {$file}");
        return $this->fsio->get($file);
    }

    protected function saveTarget($html)
    {
        $file = $this->page->getTarget();
        $dir = dirname($file);
        if (! $this->fsio->isDir($dir)) {
            $this->logger->info("    Making directory {$dir}");
            $this->fsio->mkdir($dir);
        }

        $this->logger->info("    Saving target {$file}");
        $this->fsio->put($file, $html);
    }
}
