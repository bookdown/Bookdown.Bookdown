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
 * Interface for process objects.
 *
 * @package bookdown/bookdown
 *
 */
interface ProcessInterface
{
    /**
     *
     * Invokes the processor.
     *
     * @param Page $page The Page to process.
     *
     */
    public function __invoke(Page $page);
}
