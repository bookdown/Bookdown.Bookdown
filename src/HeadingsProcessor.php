<?php
namespace Bookdown\Content;

use DomDocument;
use DomNode;
use DomNodeList;
use DomXpath;

class HeadingsProcessor
{
    protected $page;

    protected $html;

    protected $doc;

    protected $counts = array();

    protected $headings = array();

    public function __invoke(ContentPage $page)
    {
        $this->reset($page);

        $this->loadHtml();
        if (! $this->html) {
            return;
        }

        $this->loadDomDocument();
        $this->processHeadingNodes();
        $this->saveHtml();

        $page->setProcessResult(__CLASS__, $this->headings);
    }

    protected function reset($page)
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
    }

    protected function loadHtml()
    {
        $this->html = file_get_contents($this->page->getTargetFile());
    }

    protected function saveHtml()
    {
        file_put_contents($this->page->getTargetFile(), $this->html);
    }

    protected function loadDomDocument()
    {
        $this->doc = new DomDocument();
        $this->doc->formatOutput = true;
        $this->doc->loadHtml($this->html, LIBXML_HTML_NODEFDTD);
    }

    protected function processHeadingNodes()
    {
        $nodes = $this->getHeadingNodes();
        $this->setItemTitle($nodes);
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

    protected function setItemTitle(DomNodeList $nodes)
    {
        if ($this->page->getTitle()) {
            return;
        }

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
        $node->nodeValue = "{$heading->number} {$node->nodeValue}";
        $node->setAttribute('id', $heading->id);
    }

    protected function newHeading(DomNode $node)
    {
        // the full heading number
        $number = $this->getHeadingNumber($node);

        // lose the trailing dot for the ID
        $id = substr($number, 0, -1);

        // strip the leading <hN> and the closing </hN>
        // this assumes the <hN> tag has no attributes
        $title = substr($node->C14N(), 4, -5);

        return (object) array(
            'number' => $number,
            'level' => substr_count($number, '.'),
            'id' => $id,
            'href' => $this->page->getAbsoluteHref() . '#' . $id,
            'title' => $title,
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
