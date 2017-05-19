<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
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

/**
 *
 * Processes Page objects to add Heading objects.
 *
 * @package bookdown/bookdown
 *
 */
class HeadingsProcess implements ProcessInterface
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
     * The HTML from the page being processed.
     *
     * @var string
     *
     */
    protected $html;

    /**
     *
     * A DomDocument of the page HTML.
     *
     * @var DomDocument
     *
     */
    protected $doc;

    /**
     *
     * The count of h2, h3, etc. headings on the page.
     *
     * @var array
     *
     */
    protected $counts = [];

    /**
     *
     * Heading objects collected from parsing the page.
     *
     * @var array
     *
     */
    protected $headings = [];

    /**
     *
     * A factory for Heading objects.
     *
     * @var HeadingFactory
     *
     */
    protected $headingFactory;

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
     * The numbering style to use for headings.
     *
     * @param string
     *
     */
    protected $numbering;

    /**
     *
     * Constructor.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param Fsio $fsio A filesystem I/O object.
     *
     * @param HeadingFactory A factory for heading objects.
     *
     * @param string $numbering The numbering style to use for headings.
     *
     */
    public function __construct(
        LoggerInterface $logger,
        Fsio $fsio,
        HeadingFactory $headingFactory,
        $numbering
    ) {
        $this->logger = $logger;
        $this->fsio = $fsio;
        $this->headingFactory = $headingFactory;
        $this->numbering = $numbering;
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

    /**
     *
     * Resets this processor for a new Page.
     *
     * @param Page $page The page to process.
     *
     */
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

    /**
     *
     * Loads the $html property from the rendered page.
     *
     */
    protected function loadHtml()
    {
        $this->html = $this->fsio->get($this->page->getTarget());
    }

    /**
     *
     * Saves the processed HTML back to the rendered page.
     *
     */
    protected function saveHtml()
    {
        $this->fsio->put($this->page->getTarget(), $this->html);
    }

    /**
     *
     * Loads the HTML into a DomDocument.
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
     * Adds heading objects from Dom nodes.
     *
     */
    protected function processHeadingNodes()
    {
        $nodes = $this->getHeadingNodes();
        $this->setPageTitle($nodes);
        $this->addHeadings($nodes);
        $this->setHtmlFromDomDocument();
    }

    /**
     *
     * Gets heading nodes from the DomDocument.
     *
     * @return DomNodeList
     *
     */
    protected function getHeadingNodes()
    {
        $xpath = new DomXpath($this->doc);
        $query = '/html/body/*[self::h1 or self::h2 or self::h3 or self::h4 '
               . ' or self::h5 or self::h6]';
        return $xpath->query($query);
    }

    /**
     *
     * Sets the page title from the first DomNode.
     *
     * @param DomNodeList $nodes The heading nodes.
     *
     */
    protected function setPageTitle(DomNodeList $nodes)
    {
        $node = $nodes->item(0);
        if ($node) {
            $this->page->setTitle($node->nodeValue);
        }
    }

    /**
     *
     * Adds all DomNodeList nodes as Heading objects.
     *
     * @param DomNodeList $nodes The heading nodes.
     *
     */
    protected function addHeadings(DomNodeList $nodes)
    {
        foreach ($nodes as $node) {
            $this->addHeading($node);
        }
    }

    /**
     *
     * Adds one DomNode as a Heading object, and sets the heading number and ID
     * on the DomNode.
     *
     * @param DomNode $node The heading node.
     *
     */
    protected function addHeading(DomNode $node)
    {
        $heading = $this->newHeading($node);
        $this->headings[] = $heading;

        $number = new DOMText();

        switch ($this->numbering) {
            case false:
                $number->nodeValue = '';
                break;
            case 'decimal':
            default:
                $number->nodeValue = $heading->getNumber() . ' ';
                break;
        }

        $node->insertBefore($number, $node->firstChild);

        $node->setAttribute('id', $heading->getAnchor());
    }

    /**
     *
     * Creates a new Heading object from a DomNode.
     *
     * @param DomNode $node The heading node.
     *
     * @return Heading
     *
     */
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

    /**
     *
     * Gets the heading number from a DomNode.
     *
     * @param DomNode $node The heading node.
     *
     * @return string
     *
     */
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

    /**
     *
     * Given a DomNode, increment or reset the h2/h3/etc counts.
     *
     * @param DomNode $node The heading node.
     *
     */
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

    /**
     *
     * Retains modified HTML from the DomDocument manipulations.
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
