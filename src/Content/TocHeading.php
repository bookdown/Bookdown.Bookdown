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
 * Represents the tocHeading on a index page.
 *
 * @package bookdown/bookdown
 *
 */
class TocHeading extends Heading
{
    /**
     * @var TocHeadingIterator
     */
    protected $children;

    /**
     * @return TocHeadingIterator
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param TocHeadingIterator $children
     */
    public function setChildren(TocHeadingIterator $children)
    {
        $this->children = $children;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return count($this->getChildren()) > 0;
    }
}
