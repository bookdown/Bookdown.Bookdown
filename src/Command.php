<?php
namespace Bookdown\Bookdown;

use Aura\Html;
use Aura\View;
use Bookdown\Bookdown\Config;
use Bookdown\Bookdown\Content;
use Bookdown\Bookdown\Processor;
use League\CommonMark\CommonMarkConverter;

class Command
{
    protected $origin;
    protected $target;
    protected $root;

    public function __invoke($server)
    {
        $this->init($server);
        $this->collectPages();
        $this->processPages();
    }

    protected function init($server)
    {
        if (! isset($server['argv'][1])) {
            throw new Exception(
                "Please enter an origin bookdown.json file as the first argument."
            );
        }
        $this->origin = $server['argv'][1];
    }

    protected function collectPages()
    {
        $pageCollector = new Content\PageCollector(
            new Content\PageBuilder(
                new Config\ConfigBuilder()
            )
        );
        $this->root = $pageCollector($this->origin);
    }

    protected function processPages()
    {
        $processor = $this->newProcessor();
        $processor($this->root);
    }

    protected function newProcessor()
    {
        $view = $this->newView();
        $templates = $this->root->getConfig()->getTemplates();

        return new Processor\Processor(array(
            new Processor\HtmlProcessor(new CommonMarkConverter()),
            new Processor\HeadingsProcessor(new Content\HeadingFactory()),
            new Processor\TocProcessor(),
            new Processor\LayoutProcessor($view, $templates),
        ));
    }

    protected function newView()
    {
        $helpersFactory = new Html\HelperLocatorFactory();
        $helpers = $helpersFactory->newInstance();
        $viewFactory = new View\ViewFactory();
        return $viewFactory->newInstance($helpers);
    }
}
