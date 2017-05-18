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
class HeadingFactory
{
    public function newInstance($number, $title, $href, $id = null)
    {
        return new Heading($number, $title, $href, $id);
    }
}
