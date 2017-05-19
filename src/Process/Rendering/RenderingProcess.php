<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Process\Rendering;

use Psr\Log\LoggerInterface;
use Aura\View\View;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessInterface;

/**
 *
 * Renders the origin Markdown of a Page to an HTML file.
 *
 * @package bookdown/bookdown
 *
 */
class RenderingProcess implements ProcessInterface
{
    /**
     *
     * A logger implementation.
     *
     * @var LoggerInterface
     *
     */
    protected $logger;

    /**
     *
     * A filesystem I/O object.
     *
     * @var Fsio
     *
     */
    protected $fsio;

    /**
     *
     * A template view object.
     *
     * @param View
     *
     */
    protected $view;

    /**
     *
     * Constructor.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param Fsio $fsio A filesystem I/O object.
     *
     * @param View $view A template view object.
     *
     */
    public function __construct(
        LoggerInterface $logger,
        Fsio $fsio,
        View $view
    ) {
        $this->logger = $logger;
        $this->fsio = $fsio;
        $this->view = $view;
    }

    /**
     *
     * Invokes the processor.
     *
     * @param Page $page The Page to process.
     *
     */
    public function __invoke(Page $page)
    {
        $file = $page->getTarget();
        $this->logger->info("    Rendering {$file}");
        $this->view->page = $page;
        $this->view->html = '<div id="section-main">'
            .$this->fsio->get($page->getTarget())
            .'</div>';
        $result = $this->view->__invoke();
        $this->fsio->put($file, $result);
    }
}
