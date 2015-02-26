<?php
namespace Bookdown\Bookdown\Process;

use Aura\Html\HelperLocatorFactory;
use Aura\View\View;
use Aura\View\ViewFactory;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;

class RenderingProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config)
    {
        $fsio = $this->newFsio();
        $view = $this->newView($config);
        return new RenderingProcess($fsio, $view);
    }

    protected function newFsio()
    {
        return new Fsio();
    }

    protected function newView(RootConfig $config)
    {
        $helpersFactory = new HelperLocatorFactory();
        $helpers = $helpersFactory->newInstance();

        $viewFactory = new ViewFactory();
        $view = $viewFactory->newInstance($helpers);

        $this->registerTemplates($view, $config);
        $this->setTemplateName($view, $config);

        return $view;
    }

    protected function registerTemplates(View $view, RootConfig $config)
    {
        $registry = $view->getViewRegistry();

        // defaults
        $templates = $this->getTemplates();
        foreach ($templates as $name => $template) {
            $registry->set($name, $template);
        }

        // overrides
        $dir = $config->getDir();
        $templates = (array) $config->getTemplates();
        foreach ($templates as $name => $template) {
            $registry->set($name, "{$dir}/{$template}");
        }
    }

    protected function setTemplateName(View $view, RootConfig $config)
    {
        $templateName = $config->getTemplateName();
        if (! $templateName) {
            $templateName = $this->getTemplateName();
        }
        $view->setView($templateName);
    }

    protected function getTemplates()
    {
        $dir = __DIR__ . '/templates';
        return array(
            "main" => "{$dir}/main.php",
            "head" => "{$dir}/head.php",
            "body" => "{$dir}/body.php",
            "navheader" => "{$dir}/navheader.php",
            "navfooter" => "{$dir}/navfooter.php",
            "toc" => "{$dir}/toc.php",
        );
    }

    protected function getTemplateName()
    {
        return 'main';
    }
}
