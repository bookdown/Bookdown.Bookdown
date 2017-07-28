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
use Aura\Html\HelperLocatorFactory;
use Aura\View\View;
use Aura\View\ViewFactory;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Exception;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

/**
 *
 * Builds a RenderingProcess object.
 *
 * @package bookdown/bookdown
 *
 */
class RenderingProcessBuilder implements ProcessBuilderInterface
{
    /**
     *
     * Returns a new RenderingProcess object.
     *
     * @param RootConfig $config The root-level config object.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param Fsio $fsio A filesystem I/O object.
     *
     * @return RenderingProcess
     *
     */
    public function newInstance(RootConfig $config, LoggerInterface $logger, Fsio $fsio)
    {
        return new RenderingProcess(
            $logger,
            $fsio,
            $this->newView($config)
        );
    }

    /**
     *
     * Returns a new View object.
     *
     * @param RootConfig $config The root-level config object.
     *
     * @return View
     *
     */
    protected function newView(RootConfig $config)
    {
        $helpersFactory = new HelperLocatorFactory();
        $helpers = $helpersFactory->newInstance();

        $viewFactory = new ViewFactory();
        $view = $viewFactory->newInstance($helpers);

        $this->setTemplate($view, $config);

        return $view;
    }

    /**
     *
     * Sets the main template into a View object.
     *
     * @param View $view The View object.
     *
     * @param RootConfig $config The root-level config object.
     *
     */
    protected function setTemplate(View $view, RootConfig $config)
    {
        $template = $config->getTemplate();
        if (! $template) {
            $template = dirname(dirname(dirname(__DIR__))) . '/vendor/bookdown/themes/templates/main.php';
        }

        if (! file_exists($template) && ! is_readable($template)) {
            throw new Exception("Cannot find template '$template'.");
        }

        $registry = $view->getViewRegistry();
        $registry->set('__BOOKDOWN__', $template);

        $view->setView('__BOOKDOWN__');
    }
}
