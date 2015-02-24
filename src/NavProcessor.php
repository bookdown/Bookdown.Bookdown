<?php
namespace Bookdown\Content;

class NavProcessor
{
    protected $page;

    protected $header;

    protected $footer;

    protected $strtr;

    public function __invoke(ContentPage $page)
    {
        $this->page = $page;
        $this->setStrtr();
        $this->setHeader();
        $this->setFooter();
        $this->wrapHtml();
    }

    protected function setStrtr()
    {
        $this->strtr = array(
            '{NUMBER}' => $this->page->getNumber(),
            '{TITLE}' => $this->page->getTitle(),
            '{PREV_HREF}' => null,
            '{PREV_NUMBER}' => null,
            '{PREV_TITLE}' => null,
            '{PARENT_HREF}' => null,
            '{PARENT_NUMBER}' => null,
            '{PARENT_TITLE}' => null,
            '{NEXT_HREF}' => null,
            '{NEXT_NUMBER}' => null,
            '{NEXT_TITLE}' => null,
        );

        $prev = $this->page->getPrev();
        if ($prev) {
            $this->strtr['{PREV_HREF}'] = $prev->getAbsoluteHref();
            $this->strtr['{PREV_NUMBER}'] = $prev->getNumber();
            $this->strtr['{PREV_TITLE}'] = $prev->getTitle();
        }

        $next = $this->page->getNext();
        if ($next) {
            $this->strtr['{NEXT_HREF}'] = $next->getAbsoluteHref();
            $this->strtr['{NEXT_NUMBER}'] = $next->getNumber();
            $this->strtr['{NEXT_TITLE}'] = $next->getTitle();
        }

        $parent = $this->page->getParent();
        if ($parent) {
            $this->strtr['{PARENT_HREF}'] = $parent->getAbsoluteHref();
            $this->strtr['{PARENT_NUMBER}'] = $parent->getNumber();
            $this->strtr['{PARENT_TITLE}'] = $parent->getTitle();
        }
    }

    protected function setHeader()
    {
        $tpl = <<<TPL
<nav class="navheader">
    <table width="100%">
        <tr>
            <th colspan="3" align="center">{NUMBER} {TITLE}</th>
        </tr>
        <tr>
            <td width="20%" align="left"><a href="{PREV_HREF}">{PREV_TITLE}</a></td>
            <td width="60%" align="center">{PARENT_NUMBER} {PARENT_TITLE}</th>
            <td width="20%" align="right"> <a href="{NEXT_HREF}">{NEXT_TITLE}</a></td>
        </tr>
    </table>
</nav>
TPL;

        $this->header = strtr($tpl, $this->strtr);
    }

    protected function setFooter()
    {
        $tpl = <<<TPL
<nav class="navfooter">
    <table width="100%">
        <tr>
            <td width="40%" align="left"><a href="{PREV_HREF}">Prev</a></td>
            <td width="20%" align="center"><a href="{PARENT_HREF}">Up</a></td>
            <td width="40%" align="right"> <a href="{NEXT_HREF}">Next</a></td>
        </tr>
        <tr>
            <td width="40%" align="left" valign="top">{PREV_NUMBER} {PREV_TITLE}</td>
            <td width="20%" align="center" valign="top">{PARENT_NUMBER} {PARENT_TITLE}</a></td>
            <td width="40%" align="right" valign="top">{NEXT_NUMBER} {NEXT_TITLE}</td>
        </tr>
    </table>
</nav>
TPL;

        $this->footer = strtr($tpl, $this->strtr);

    }

    protected function wrapHtml()
    {
        $file = $this->page->getTargetFile();
        $html = file_get_contents($file);
        $html = $this->header . PHP_EOL
            . trim($html) . PHP_EOL
            . $this->footer . PHP_EOL;
        file_put_contents($file, $html);
    }
}
