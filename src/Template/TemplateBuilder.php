<?php
namespace Bookdown\Bookdown\Template;

use Aura\Html\HelperLocatorFactory;
use Aura\View\View;
use Aura\View\ViewFactory;
use Bookdown\Bookdown\Config\RootConfig;

class TemplateBuilder implements TemplateBuilderInterface
{
    public function newInstance(RootConfig $config)
    {
        $view = $this->newView($config);
        return new Template($view);
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

        // default templates
        $dir = __DIR__ . '/files';
        $templates = array(
            'main' => 'main.php',
            'head' => 'head.php',
            'body' => 'body.php',
            'navheader' => 'navheader.php',
            'navfooter' => 'navfooter.php',
            'toc' => 'toc.php',
        );
        foreach ($templates as $name => $template) {
            $registry->set($name, "{$dir}/{$template}");
        }

        // overrides
        $dir = $config->getDir();
        $templates = (array) $config->get('templates');
        foreach ($templates as $name => $template) {
            $registry->set($name, "{$dir}/{$template}");
        }
    }

    protected function setTemplateName(View $view, RootConfig $config)
    {
        $templateName = $config->get('templateName');
        if (! $templateName) {
            $templateName = 'main';
        }
        $view->setView($templateName);
    }
}
