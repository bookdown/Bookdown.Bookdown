<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Process\Conversion;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Exception;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;
use League\CommonMark\Converter;

/**
 *
 * Converts CommandMark Markdown to HTML.
 *
 * @package bookdown/bookdown
 *
 */
class ConversionProcess implements ProcessInterface
{
    /**
     *
     * The page being processed.
     *
     * @var Page
     *
     */
    protected $page;

    /**
     *
     * A logger implementation.
     *
     * @var LoggerInterface
     *
     */
    protected $logger;

    /**
     *
     * A filesystem I/O object.
     *
     * @var Fsio
     *
     */
    protected $fsio;

    /**
     *
     * CommonMark-to-HTML converter.
     *
     * @var Converter
     *
     */
    protected $commonMarkConverter;

    /**
     *
     * Constructor.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param Fsio $fsio A filesystem I/O object.
     *
     * @param Converter $converter A CommonMark-to-HTML converter.
     *
     */
    public function __construct(
        LoggerInterface $logger,
        Fsio $fsio,
        Converter $commonMarkConverter
    ) {
        $this->logger = $logger;
        $this->fsio = $fsio;
        $this->commonMarkConverter = $commonMarkConverter;
    }

    /**
     *
     * Invokes the processor.
     *
     * @param Page $page The Page to process.
     *
     */
    public function __invoke(Page $page)
    {
        $this->page = $page;
        $text = $this->readOrigin();
        $html = $this->commonMarkConverter->convertToHtml($text);
        $this->saveTarget($html);
    }

    /**
     *
     * Returns the origin file Markdown.
     *
     * @return string
     *
     */
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

    /**
     *
     * Saves the converted HTML file.
     *
     * @param string $html The HTML converted from Markdown.
     *
     */
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
