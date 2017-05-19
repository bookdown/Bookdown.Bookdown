<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Process\Resource;

use Bookdown\Bookdown\Config\RootConfig;
use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;
use DomDocument;
use DomNode;
use DomNodeList;
use DomXpath;

/**
 *
 * Copies image files from a Page to the target rendering location.
 *
 * @package bookdown/bookdown
 *
 */
class CopyImageProcess implements ProcessInterface
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
     * The root-level config object.
     *
     * @var RootConfig
     *
     */
    protected $config;

    /**
     *
     * The HTML from the rendered page.
     *
     * @var string
     *
     */
    protected $html;

    /**
     *
     * A DomDocument of the HTML.
     *
     * @var DomDocument
     *
     */
    protected $doc;

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
     * Constructor.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param Fsio $fsio A filesystem I/O object.
     *
     * @param RootConfig $config The root-level config object.
     *
     */
    public function __construct(
        LoggerInterface $logger,
        Fsio $fsio,
        RootConfig $config
    ) {
        $this->logger = $logger;
        $this->fsio = $fsio;
        $this->config = $config;
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
        $this->logger->info("    Processing copy images for {$page->getTarget()}");

        $this->reset($page);

        $this->loadHtml();
        if ($this->html) {
            $this->loadDomDocument();
            $this->processImageNodes();
            $this->saveHtml();
        }
    }

    /**
     *
     * Resets the processor for the Page to be processed.
     *
     * @param Page $page The page to be processed.
     *
     */
    protected function reset(Page $page)
    {
        $this->page = $page;
        $this->html = null;
        $this->doc = null;
    }

    /**
     *
     * Loads the HTML from the rendered page.
     *
     */
    protected function loadHtml()
    {
        $this->html = $this->fsio->get($this->page->getTarget());
    }

    /**
     *
     * Save the modified HTML back to the rendered page.
     *
     */
    protected function saveHtml()
    {
        $this->fsio->put($this->page->getTarget(), $this->html);
    }

    /**
     *
     * Creates a DomDocument from the page HTML.
     *
     */
    protected function loadDomDocument()
    {
        $this->doc = new DomDocument();
        $this->doc->formatOutput = true;
        $this->doc->loadHtml(
            mb_convert_encoding($this->html, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NODEFDTD
        );
    }

    /**
     *
     * Finds and retains all images in the DomDocument.
     *
     */
    protected function processImageNodes()
    {
        $nodes = $this->getImageNodes();
        $this->addImages($nodes);
        $this->setHtmlFromDomDocument();
    }

    /**
     *
     * Gets all the images nodes in the DomDocument.
     *
     * @return DomNodeList
     *
     */
    protected function getImageNodes()
    {
        $xpath = new DomXpath($this->doc);
        $query = '//img';
        return $xpath->query($query);
    }

    /**
     *
     * Retains all the image nodes.
     *
     * @param DomNodeList $nodes The image nodes.
     *
     */
    protected function addImages(DomNodeList $nodes)
    {
        foreach ($nodes as $node) {
            $this->addImage($node);
        }
    }

    /**
     *
     * Adds one image.
     *
     * @param DomNode $node The image node.
     *
     */
    protected function addImage(DomNode $node)
    {
        if ($src = $this->downloadImage($node)) {
            $node->attributes->getNamedItem('src')->nodeValue = $src;
        }
    }

    /**
     *
     * Copies the image to the rendering location.
     *
     * @param DomNode $node The image node.
     *
     * @throws Exception on error.
     *
     */
    protected function downloadImage(DomNode $node)
    {
        $image = $node->attributes->getNamedItem('src')->nodeValue;

        // no image or absolute URI
        if (! $image || preg_match('#^(http(s)?|//)#', $image)) {
            return '';
        }
        $imageName = basename($image);
        $originFile = dirname($this->page->getOrigin()) . '/' . ltrim($image, '/');

        $dir = dirname($this->page->getTarget()) . '/img/';
        $file = $dir . $imageName;

        if (!$this->fsio->isDir($dir)) {
            $this->fsio->mkdir($dir);
        }

        try {
            $this->fsio->put($file, $this->fsio->get($originFile));
        } catch (\Exception $e) {
            $this->logger->warning("      Image {$originFile} does not exist.");
        }
        return $this->config->getRootHref() . (str_replace($this->config->getTarget(), '', $dir)) . $imageName;
    }

    /**
     *
     * Retains the modified DomDocument HTML.
     *
     */
    protected function setHtmlFromDomDocument()
    {
        // retain the modified html
        $this->html = trim($this->doc->saveHtml($this->doc->documentElement));

        // strip the html and body tags added by DomDocument
        $this->html = substr(
            $this->html,
            strlen('<html><body>'),
            -1 * strlen('</body></html>')
        );

        // still may be whitespace all about
        $this->html = trim($this->html) . PHP_EOL;
    }
}
