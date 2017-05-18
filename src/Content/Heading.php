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
 *
 *
 * @package bookdown/bookdown
 *
 */
class Heading
{
    protected $number;

    protected $title;

    protected $id;

    protected $href;

    protected $level;

    public function __construct($number, $title, $href, $id = null)
    {
        $this->number = $number;
        $this->title = $title;
        $this->href = $href;
        $this->id = $id;
        $this->level = substr_count($number, '.');
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getHref()
    {
        $href = $this->href;
        if ($this->id) {
            $href .= $this->getHrefAnchor();
        }
        return $href;
    }

    /**
     * Creates a complete anchor href attribute for links.
     *
     * @return string
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
     * Return a valid anchor string tag to use as html id attribute.
     *
     * @return string
     */
    public function getAnchor()
    {
        $anchor = null;

        if ($this->id) {
            $anchor = str_replace('.', '-', $this->getId());
        }
        return $anchor;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function asArray()
    {
        return get_object_vars($this);
    }
}
