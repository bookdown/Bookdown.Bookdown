<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\Content\Page;

/**
 *
 *
 *
 * @package bookdown/bookdown
 *
 */
interface ProcessInterface
{
    public function __invoke(Page $page);
}
