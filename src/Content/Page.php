<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Content;

/**
 *
 * A generic content page.
 *
 * @package bookdown/bookdown
 *
 */
class Page
{
    /**
     *
     * The name of this page.
     *
     * @var string $name
     *
     */
    protected $name;

    /**
     *
     * The origin file for this page.
     *
     * @var string
     *
     */
    protected $origin;

    /**
     *
     * The parent Page.
     *
     * @var IndexPage
     *
     */
    protected $parent;

    /**
     *
     * The position of this page at the current TOC level.
     *
     * @var int
     *
     */
    protected $count;

    /**
     *
     * The previous Page, if any.
     *
     * @var Page
     *
     */
    protected $prev;

    /**
     *
     * The next Page, if any.
     *
     * @var Page
     *
     */
    protected $next;

    /**
     *
     * The title for this Page.
     *
     * @var string
     *
     */
    protected $title;

    /**
     *
     * The array of Heading objects for this Page.
     *
     * @var array
     *
     */
    protected $headings = [];

    /**
     *
     * The copyright string for this Page.
     *
     * @var string
     *
     */
    protected $copyright;

    /**
     *
     * Constructor.
     *
     * @param string $origin The origin file for this page.
     *
     * @param string $name The name of this page.
     *
     * @param IndexPage $parent The parent of this page.
     *
     * @param int $count The position of this page at the current level.
     *
     */
    public function __construct(
        $origin,
        $name,
        IndexPage $parent,
        $count
    ) {
        $this->origin = $origin;
        $this->name = $name;
        $this->parent = $parent;
        $this->count = $count;
    }

    /**
     *
     * Returns the name for this page.
     *
     * @return string
     *
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * Returns the origin path of this Page.
     *
     * @return string
     *
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     *
     * Sets the title of this Page.
     *
     * @param string $title The title of this page.
     *
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     *
     * Returns the title of this Page.
     *
     * @return string
     *
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * Does this Page have a parent?
     *
     * @return bool
     *
     */
    public function hasParent()
    {
        return (bool) $this->parent;
    }

    /**
     *
     * Returns the parent page, if any.
     *
     * @return IndexPage|null
     *
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     *
     * Returns this page's position in the TOC at this level.
     *
     * @return int
     *
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     *
     * Returns the TOC depth level of this Page.
     *
     * @return int
     *
     */
    public function getLevel()
    {
        if ($this->hasParent()) {
            return $this->parent->getLevel() + 1;
        }

        return 0;
    }

    /**
     *
     * Sets the Page previous to this one.
     *
     * @param Page $prev The page previous to this one.
     *
     */
    public function setPrev(Page $prev)
    {
        $this->prev = $prev;
    }

    /**
     *
     * Is there a Page previous to this one?
     *
     * @return bool
     *
     */
    public function hasPrev()
    {
        return (bool) $this->prev;
    }

    /**
     *
     * Returns the page previous to this one, if any.
     *
     * @return Page|null
     *
     */
    public function getPrev()
    {
        return $this->prev;
    }

    /**
     *
     * Sets the next Page after this one, if any.
     *
     * @param Page $next The next page.
     *
     */
    public function setNext(Page $next)
    {
        $this->next = $next;
    }

    /**
     *
     * Is there a Page after this one?
     *
     * @return bool
     *
     */
    public function hasNext()
    {
        return (bool) $this->next;
    }

    /**
     *
     * Returns the next Page after this one, if any.
     *
     * @return Page|null
     *
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     *
     * Returns the href attribute for linking to this page.
     *
     * @return string
     *
     */
    public function getHref()
    {
        $base = $this->getParent()->getHref();
        return $base . $this->getName() . '.html';
    }

    /**
     *
     * Returns the full number for this page.
     *
     * @return string
     *
     */
    public function getNumber()
    {
        $base = $this->getParent()->getNumber();
        $count = $this->getCount();
        return "{$base}{$count}.";
    }

    /**
     *
     * Returns the full number-and-title for this page.
     *
     * @return string
     *
     */
    public function getNumberAndTitle()
    {
        return trim($this->getNumber() . ' ' . $this->getTitle());
    }

    /**
     *
     * Returns the target path for output from this page.
     *
     * @return string
     *
     */
    public function getTarget()
    {
        $base = rtrim(
            dirname($this->getParent()->getTarget()),
            DIRECTORY_SEPARATOR
        );
        return $base . DIRECTORY_SEPARATOR . $this->getName() . '.html';
    }

    /**
     *
     * Returns the copyright string for this page.
     *
     * @return string
     *
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     *
     * Sets the copyright string for this page.
     *
     * @param string $copyright The copyright string.
     *
     *
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
    }

    /**
     *
     * Sets the heading objects for this page.
     *
     * @param array $headings An array of Heading objects.
     *
     */
    public function setHeadings(array $headings)
    {
        $this->headings = $headings;
    }

    /**
     *
     * Does this page have any headings?
     *
     * @return bool
     *
     */
    public function hasHeadings()
    {
        return (bool) $this->headings;
    }

    /**
     *
     * Returns the array of Heading objects.
     *
     * @return array
     *
     */
    public function getHeadings()
    {
        return $this->headings;
    }

    /**
     *
     * Is this an index page?
     *
     * @return bool
     *
     */
    public function isIndex()
    {
        return false;
    }

    /**
     *
     * Is this the root page?
     *
     * @return bool
     *
     */
    public function isRoot()
    {
        return false;
    }

    /**
     *
     * Returns the root page object.
     *
     * @return RootPage
     *
     */
    public function getRoot()
    {
        $page = $this;
        while (! $page->isRoot()) {
            $page = $page->getParent();
        }
        return $page;
    }
}
