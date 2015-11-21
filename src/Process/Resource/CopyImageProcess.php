<?php
namespace Bookdown\Bookdown\Process\Resource;

use Bookdown\Bookdown\Config\RootConfig;
use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;
use DomDocument;
use DomNode;
use DomXpath;

class CopyImageProcess implements ProcessInterface
{
    /**
     * @var Page
     */
    protected $page;

    /**
     * @var RootConfig
     */
    protected $config;

    /**
     * @var string
     */
    protected $html;

    /**
     * @var DomDocument
     */
    protected $doc;

    /**
     * @var Fsio
     */
    protected $fsio;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        LoggerInterface $logger,
        Fsio $fsio,
        RootConfig $config
    ) {
        $this->logger = $logger;
        $this->fsio = $fsio;
        $this->config = $config;
    }

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

    protected function reset(Page $page)
    {
        $this->page = $page;
        $this->html = null;
        $this->doc = null;
    }

    protected function loadHtml()
    {
        $this->html = $this->fsio->get($this->page->getTarget());
    }

    protected function saveHtml()
    {
        $this->fsio->put($this->page->getTarget(), $this->html);
    }

    protected function loadDomDocument()
    {
        $this->doc = new DomDocument();
        $this->doc->formatOutput = true;
        $this->doc->loadHtml(mb_convert_encoding($this->html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NODEFDTD);
    }

    protected function processImageNodes()
    {
        $nodes = $this->getImageNodes();
        $this->addImages($nodes);
        $this->setHtmlFromDomDocument();
    }

    protected function getImageNodes()
    {
        $xpath = new DomXpath($this->doc);
        $query = '//img';
        return $xpath->query($query);
    }

    protected function addImages($nodes)
    {
        foreach ($nodes as $node) {
            $this->addImage($node);
        }
    }

    protected function addImage(DomNode $node)
    {
        if ($src = $this->downloadImage($node)) {
            $node->attributes->getNamedItem('src')->nodeValue = $src;
        }
    }

    protected function downloadImage(DomNode $node)
    {
        $image = $node->attributes->getNamedItem('src')->nodeValue;

        # no image or absolute URI
        if (!$image || preg_match('#^(http(s)?|//)#', $image)) {
            return '';
        }
        $imageName = basename($image);
        $originFile = dirname($this->page->getOrigin()) . '/' . ltrim($image, '/');

        $dir = dirname($this->page->getTarget()) . '/img/';
        $file = $dir . $imageName;

        if (!$this->fsio->isDir($dir)) {
            $this->fsio->mkdir($dir);
        }
        $this->fsio->put($file, $this->fsio->get($originFile));
        return $this->config->getRootHref() . (str_replace($this->config->getTarget(), '', $dir)) . $imageName;
    }

    protected function setHtmlFromDomDocument()
    {
        // retain the modified html
        $this->html = trim($this->doc->saveHtml());

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
