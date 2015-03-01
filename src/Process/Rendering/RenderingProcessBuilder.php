<?php
namespace Bookdown\Bookdown\Process\Rendering;

use Aura\Cli\Stdio;
use Aura\Html\HelperLocatorFactory;
use Aura\View\View;
use Aura\View\ViewFactory;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

class RenderingProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, Stdio $stdio, Fsio $fsio)
    {
        return new RenderingProcess(
            $stdio,
            $fsio,
            $this->newView($config)
        );
    }

    protected function newView(RootConfig $config)
    {
        $helpersFactory = new HelperLocatorFactory();
        $helpers = $helpersFactory->newInstance();

        $viewFactory = new ViewFactory();
        $view = $viewFactory->newInstance($helpers);

        $this->setTemplate($view, $config);

        return $view;
    }

    protected function setTemplate(View $view, RootConfig $config)
    {
        $template = $config->getTemplate();
        if (! $template) {
            $template = dirname(dirname(dirname(__DIR__))) . '/templates/main.php';
        }

        $registry = $view->getViewRegistry();
        $registry->set('__BOOKDOWN__', $template);

        $view->setView('__BOOKDOWN__');
    }
}
