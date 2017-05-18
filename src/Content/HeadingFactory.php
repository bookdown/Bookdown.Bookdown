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
 * A factory for Heading objects.
 *
 * @package bookdown/bookdown
 *
 */
class HeadingFactory
{
    /**
     *
     * Returns a new Heading object.
     *
     * @param string $number The heading number.
     *
     * @param string $title The heading title.
     *
     * @param string $href The href attribute value.
     *
     * @param string $id The id attribute value.
     *
     * @return Heading
     *
     */
    public function newInstance($number, $title, $href, $id = null)
    {
        return new Heading($number, $title, $href, $id);
    }
}
