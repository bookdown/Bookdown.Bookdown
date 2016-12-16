<?php
namespace Bookdown\Bookdown\Process\Index;

use Bookdown\Bookdown\Content\Heading;
use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Content\HeadingFactory;

class IndexProcess implements ProcessInterface
{
    /**
     * @var array
     */
    protected $headings;

    /**
     * @var array
     */
    protected $contents;

    /**
     * @var array
     */
    protected $searchIndex;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Fsio
     */
    protected $fsio;

    /**
     * @var Page
     */
    protected $page;

    /**
     * @var RootConfig
     */
    protected $config;

    public function __construct(
        LoggerInterface $logger,
        Fsio $fsio,
        RootConfig $config
    )
    {
        $this->logger = $logger;
        $this->fsio = $fsio;
        $this->config = $config;
    }

    public function __invoke(Page $page)
    {
        $file = $this->config->getTarget() . 'index.json';

        $this->reset();
        $this->logger->info("    Create search index for {$page->getTarget()}");
        $this->writeIndex($page, $file);
    }

    /**
     * Write json index.
     *
     * @param Page $page
     * @param string $file
     * @throws \Bookdown\Bookdown\Exception
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
     * Create the heading and content array with related keys.
     *
     * @param string $html
     * @param Page $page
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
                    'title' => strip_tags($domDocument->saveHtml($element)),
                    'href' => $heading->getHref()
                );
            }

            // add content
            if (!$isHeading && $i > 0) {
                if (empty($this->contents[$i])) {
                    $this->contents[$i] = '';
                }
                $this->contents[$i] .= preg_replace('/\s+/', ' ', strip_tags($domDocument->saveHtml($element)));
            }
        }
    }

    /**
     * Create the content index entries related to the correct title.
     */
    protected function buildRelatedContent()
    {
        foreach ($this->getContents() as $key => $content) {
            array_push($this->searchIndex, array(
                'id' => $this->getHeadings()[$key]['href'],
                'title' => $this->getHeadings()[$key]['title'],
                'content' => $content
            ));
        }
    }

    /**
     * Get the html dom body children list.
     *
     * @param \DOMDocument $domDocument
     * @return \DOMNodeList
     */
    protected function getHtmlDomBody(\DOMDocument $domDocument)
    {
        $xpath = new \DomXpath($domDocument);

        $query = '
            //div[@id="htmlcontainer"]//h1/../* | 
            //div[@id="htmlcontainer"]//h2/../* | 
            //div[@id="htmlcontainer"]//h3/../* | 
            //div[@id="htmlcontainer"]//h4/../* | 
            //div[@id="htmlcontainer"]//h5/../* |
            //div[@id="htmlcontainer"]//h6/../*';

        return $xpath->query($query);
    }

    /**
     * Create dom document from html string.
     *
     * @param string $html
     * @return \DOMDocument
     */
    protected function createDomDocument($html)
    {
        $domDocument = new \DOMDocument();
        libxml_use_internal_errors(true);
        $domDocument->formatOutput = true;
        $domDocument->loadHtml(mb_convert_encoding(
            $html,
            'HTML-ENTITIES',
            'UTF-8'
        ), LIBXML_HTML_NODEFDTD);
        libxml_use_internal_errors(false);

        return $domDocument;
    }

    /**
     * Load html from source file
     *
     * @param Page $page
     * @return string
     */
    protected function loadHtml(Page $page)
    {
        return $this->fsio->get($page->getTarget());
    }

    /**
     * Read in the file and return json data as array.
     *
     * @param $file
     * @return array
     */
    protected function readJson($file)
    {
        $json = $this->fsio->get($file);
        return json_decode($json, true);
    }

    /**
     * Write the content array to file as json.
     *
     * @param array $content
     * @param $file
     */
    protected function writeJson(array $content, $file)
    {
        $json = json_encode($content);
        $this->fsio->put($file, $json);
    }

    /**
     * @return array
     */
    protected function getHeadings()
    {
        return $this->headings;
    }

    /**
     * @return array
     */
    protected function getContents()
    {
        return $this->contents;
    }

    /**
     * Reset class member.
     */
    protected function reset(){
        $this->headings = null;
        $this->contents = null;
    }
}
