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
 * Represents the heading on a page.
 *
 * @package bookdown/bookdown
 *
 */
class Heading
{
    /**
     *
     * The heading number.
     *
     * @var string
     *
     */
    protected $number;

    /**
     *
     * The heading title itself.
     *
     * @var string
     *
     */
    protected $title;

    /**
     *
     * The id attribute for the heading.
     *
     * @var string
     *
     */
    protected $id;

    /**
     *
     * The href attribute for the heading.
     *
     * @var string
     *
     */
    protected $href;

    /**
     *
     * The TOC depth level for the heading.
     *
     * @var int
     *
     */
    protected $level;

    /**
     *
     * Constructor.
     *
     * @param string $number The heading number.
     *
     * @param string $title The heading title.
     *
     * @param string $href The href attribute value.
     *
     * @param string $id The id attribute value.
     *
     */
    public function __construct($number, $title, $href, $id = null)
    {
        $this->number = $number;
        $this->title = $title;
        $this->href = $href;
        $this->id = $id;
        $this->level = substr_count($number, '.');
    }

    /**
     *
     * Returns the heading number.
     *
     * @return string
     *
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     *
     * Returns the heading title.
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
     * Returns the ID attribute value.
     *
     * @return string
     *
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * Returns the HREF attribute value.
     *
     * @return string
     *
     */
    public function getHref()
    {
        $href = $this->href;
        if ($this->id) {
            $href .= $this->getHrefAnchor();
        }
        return $href;
    }

    /**
     *
     * Creates a complete anchor href attribute for links.
     *
     * @return string
     *
     */
    public function getHrefAnchor()
    {
        $hrefAnchor = null;

        if ($this->id) {
            return '#' . $this->getAnchor();
        }
        return $hrefAnchor;
    }

    /**
     *
     * Return a valid anchor string tag to use as html id attribute.
     *
     * @return string
     *
     */
    public function getAnchor()
    {
        $anchor = null;

        if ($this->id) {
            $anchor = str_replace('.', '-', $this->getId());
        }
        return $anchor;
    }

    /**
     *
     * Returns the TOC depth level for this heading.
     *
     * @return int
     *
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     *
     * Returns the properties of this heading as an array.
     *
     * @return array
     *
     */
    public function asArray()
    {
        return get_object_vars($this);
    }
}
