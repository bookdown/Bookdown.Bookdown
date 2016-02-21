<?php
namespace Bookdown\Bookdown\Process\Headings;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Content\HeadingFactory;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;
use DomDocument;
use DomNode;
use DomNodeList;
use DomText;
use DomXpath;

class HeadingsProcess implements ProcessInterface
{
    protected $page;

    protected $html;

    protected $doc;

    protected $counts = array();

    protected $headings = array();

    protected $headingFactory;

    protected $fsio;

    protected $logger;

    public function __construct(
        LoggerInterface $logger,
        Fsio $fsio,
        HeadingFactory $headingFactory
    ) {
        $this->logger = $logger;
        $this->fsio = $fsio;
        $this->headingFactory = $headingFactory;
    }

    public function __invoke(Page $page)
    {
        $this->logger->info("    Processing headings for {$page->getTarget()}");

        $this->reset($page);

        $this->loadHtml();
        if ($this->html) {
            $this->loadDomDocument();
            $this->processHeadingNodes();
            $this->saveHtml();
        }

        $page->setHeadings($this->headings);
    }

    protected function reset(Page $page)
    {
        $this->page = $page;
        $this->html = null;
        $this->doc = null;
        $this->counts = array(
            'h2' => 0,
            'h3' => 0,
            'h4' => 0,
            'h5' => 0,
            'h6' => 0,
        );
        $this->headings = array();

        if ($this->page->isIndex()) {
            $this->headings[] = $this->headingFactory->newInstance(
                $this->page->getNumber(),
                $this->page->getTitle(),
                $this->page->getHref()
            );
        }
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

    protected function processHeadingNodes()
    {
        $nodes = $this->getHeadingNodes();
        $this->setPageTitle($nodes);
        $this->addHeadings($nodes);
        $this->setHtmlFromDomDocument();
    }

    protected function getHeadingNodes()
    {
        $xpath = new DomXpath($this->doc);
        $query = '/html/body/*[self::h1 or self::h2 or self::h3 or self::h4 '
               . ' or self::h5 or self::h6]';
        return $xpath->query($query);
    }

    protected function setPageTitle(DomNodeList $nodes)
    {
        $node = $nodes->item(0);
        if ($node) {
            $this->page->setTitle($node->nodeValue);
        }
    }

    protected function addHeadings($nodes)
    {
        foreach ($nodes as $node) {
            $this->addHeading($node);
        }
    }

    protected function addHeading(DomNode $node)
    {
        $heading = $this->newHeading($node);
        $this->headings[] = $heading;

        $number = new DOMText();
        $number->nodeValue = $heading->getNumber() . ' ';
        $node->insertBefore($number, $node->firstChild);

        $node->setAttribute('id', $heading->getAnchor());
    }

    protected function newHeading(DomNode $node)
    {
        // the full heading number
        $number = $this->getHeadingNumber($node);

        // strip the leading <hN> and the closing </hN>
        // this assumes the <hN> tag has no attributes
        $title = substr($node->C14N(), 4, -5);

        // lose the trailing dot for the ID
        $id = substr($number, 0, -1);

        return $this->headingFactory->newInstance(
            $number,
            $title,
            $this->page->getHref(),
            $id
        );
    }

    protected function getHeadingNumber(DomNode $node)
    {
        $this->setCounts($node);
        $string = '';
        foreach ($this->counts as $count) {
            if (! $count) {
                break;
            }
            $string .= "{$count}.";
        }
        return $this->page->getNumber() . $string;
    }

    protected function setCounts(DomNode $node)
    {
        foreach ($this->counts as $level => $count) {
            if ($level == $node->nodeName) {
                $this->counts[$level] ++;
            }
            if ($level > $node->nodeName) {
                $this->counts[$level] = 0;
            }
        }
    }

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
