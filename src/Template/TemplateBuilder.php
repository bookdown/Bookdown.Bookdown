<?php
namespace Bookdown\Bookdown\Template;

use Aura\Html;
use Aura\View;
use Bookdown\Bookdown\Config\RootConfig;

class TemplateBuilder implements TemplateBuilderInterface
{
    public function newInstance(RootConfig $config)
    {
        return new Template($this->newView($config));
    }

    protected function newView(RootConfig $config)
    {
        $helpersFactory = new Html\HelperLocatorFactory();
        $helpers = $helpersFactory->newInstance();

        $viewFactory = new View\ViewFactory();
        $view = $viewFactory->newInstance($helpers);

        $dir = $config->getDir() . 'templates';
        $templates = array(
            'default' => "{$dir}/default.php",
            'navheader' => "{$dir}/navheader.php",
            'navfooter' => "{$dir}/navfooter.php",
            'toc' => "{$dir}/files/toc.php",
        );

        $registry = $view->getViewRegistry();
        foreach ($templates as $name => $template) {
            $registry->set($name, $template);
        }

        $view->setView('default');
        return $view;
    }
}
