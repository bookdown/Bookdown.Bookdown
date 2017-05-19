<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Process\Index;

use Bookdown\Bookdown\Content\Heading;
use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Exception;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Content\HeadingFactory;

/**
 *
 * Processes a page to build a search index for it.
 *
 * @package bookdown/bookdown
 *
 */
class IndexProcess implements ProcessInterface
{
    /**
     *
     * Heading information for the search index.
     *
     * @var array
     */
    protected $headings;

    /**
     *
     * Contents for the search index.
     *
     * @var array
     *
     */
    protected $contents;

    /**
     *
     * The search index JSON array.
     *
     * @var array
     *
     */
    protected $searchIndex;

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
        $file = $this->config->getTarget() . 'index.json';

        $this->reset();
        $this->logger->info("    Create search index for {$page->getTarget()}");
        $this->writeIndex($page, $file);
    }

    /**
     *
     * Writes page search data to the JSON search index file.
     *
     * @param Page $page The page being processed.
     *
     * @param string $file The search index file.
     *
     * @throws Exception on error.
     *
     */
    protected function writeIndex(Page $page, $file)
    {
        if ($page->isRoot()) {
            $this->fsio->put($file, json_encode(array()));
            return;
        }

        if ($page->isIndex()) {
            return;
        }

        $html = $this->loadHtml($page);
        $this->processHtml($html, $page);

        $this->searchIndex = $this->readJson($file);
        $this->buildRelatedContent();
        $this->writeJson($this->searchIndex, $file);
    }

    /**
     *
     * Create the heading and content array with related keys.
     *
     * @param string $html The HTML from the page.
     *
     * @param Page $page The page being processed.
     *
     */
    protected function processHtml($html, Page $page)
    {
        $i = 0;
        $headingTags = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
        $domDocument = $this->createDomDocument($html);
        $elements = $this->getHtmlDomBody($domDocument);

        foreach ($elements as $element) {

            $isHeading = in_array($element->nodeName, $headingTags);

            // add heading
            if ($isHeading) {
                /* @var Heading $heading */
                $heading = $page->getHeadings()[$i];
                $i++;
                $this->headings[$i] = array(
                    'title' => strip_tags($domDocument->saveHTML($element)),
                    'href' => $heading->getHref()
                );
            }

            // add content
            if (!$isHeading && $i > 0) {
                if (empty($this->contents[$i])) {
                    $this->contents[$i] = '';
                }
                $this->contents[$i] .= preg_replace('/\s+/', ' ', strip_tags($domDocument->saveHTML($element)));
            }
        }
    }

    /**
     *
     * Creates the content index entries related to the correct title.
     *
     */
    protected function buildRelatedContent()
    {
        $contents = $this->getContents();

        if (count($contents) > 0) {
            foreach ($contents as $key => $content) {
                array_push($this->searchIndex, array(
                    'id' => utf8_encode($this->getHeadings()[$key]['href']),
                    'title' => utf8_encode($this->getHeadings()[$key]['title']),
                    'content' => utf8_encode($content)
                ));
            }
        }
    }

    /**
     *
     * Gets the html dom body children list.
     *
     * @param \DOMDocument $domDocument The DomDocument for the page.
     *
     * @return \DOMNodeList
     *
     */
    protected function getHtmlDomBody(\DOMDocument $domDocument)
    {
        $xpath = new \DomXpath($domDocument);
        $query = '//div[@id="section-main"]//h1/../*|//div[@id="section-main"]//h2/../*|//div[@id="section-main"]//h3/../*|//div[@id="section-main"]//h4/../*|//div[@id="section-main"]//h5/../*|//div[@id="section-main"]//h6/../*';
        return $xpath->query($query);
    }

    /**
     *
     * Creates a DomDocument from the page HTML.
     *
     * @param string $html The Page HTML.
     *
     * @return \DOMDocument
     *
     */
    protected function createDomDocument($html)
    {
        $domDocument = new \DOMDocument();
        libxml_use_internal_errors(true);
        $domDocument->formatOutput = true;
        $domDocument->loadHTML(mb_convert_encoding(
            $html,
            'HTML-ENTITIES',
            'UTF-8'
        ), LIBXML_HTML_NODEFDTD);
        libxml_use_internal_errors(false);

        return $domDocument;
    }

    /**
     *
     * Returns HTML from the rendered Page file.
     *
     * @param Page $page The page being processed.
     *
     * @return string
     *
     */
    protected function loadHtml(Page $page)
    {
        return $this->fsio->get($page->getTarget());
    }

    /**
     *
     * Read in the search index file and returns JSON data as array.
     *
     * @param string $file The search index JSON file.
     *
     * @return array
     *
     */
    protected function readJson($file)
    {
        $json = $this->fsio->get($file);
        return json_decode($json, true);
    }

    /**
     *
     * Writes the content array to the search index JSON file.
     *
     * @param array $content The content to put into the JSON file.
     *
     * @param string $file The file to write to.
     *
     */
    protected function writeJson(array $content, $file)
    {
        $json = json_encode($content);
        if ($json === false) {
            throw new Exception(json_last_error_msg(), json_last_error());
        }
        $this->fsio->put($file, $json);
    }

    /**
     *
     * Returns the search index headings.
     *
     * @return array
     *
     */
    protected function getHeadings()
    {
        return $this->headings;
    }

    /**
     *
     * Returns the new search index contents.
     *
     * @return array
     *
     */
    protected function getContents()
    {
        return $this->contents;
    }

    /**
     *
     * Resets the processor for a new page.
     *
     */
    protected function reset()
    {
        $this->headings = null;
        $this->contents = null;
    }
}
