<?php
namespace Bookdown\Bookdown;

use Aura\Html;
use Aura\View;
use Bookdown\Bookdown\Content;
use Bookdown\Bookdown\Processor;
use League\CommonMark\CommonMarkConverter;

class Command
{
    public function __invoke($server)
    {
        if (! isset($server['argv'][1])) {
            throw new Exception(
                "Please enter an origin bookdown.json file as the first argument."
            );
        }
        $origin = $server['argv'][1];

        if (! isset($server['argv'][2])) {
            throw new Exception(
                "Please enter a writable target directory as the second argument."
            );
        }
        $target = $server['argv'][2];

        $pageCollector = new Content\PageCollector(new Content\PageFactory(), $target);
        $root = $pageCollector($origin);

        $helpersFactory = new Html\HelperLocatorFactory();
        $helpers = $helpersFactory->newInstance();

        $viewFactory = new View\ViewFactory();
        $view = $viewFactory->newInstance($helpers);

        $templatesDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates';
        $templates = array(
            'default' => "{$templatesDir}/default.php",
            'navheader' => "{$templatesDir}/navheader.php",
            'navfooter' => "{$templatesDir}/navfooter.php",
            'toc' => "{$templatesDir}/toc.php",
        );

        $processor = new Processor\Processor(array(

            // basic HTML conversion
            new Processor\HtmlProcessor(new CommonMarkConverter()),

            // extract and number headings
            new Processor\HeadingsProcessor(new Content\HeadingFactory()),

            // add TOC entries
            new Processor\TocProcessor(),

            // final layout
            new Processor\LayoutProcessor($view, $templates),
        ));

        $processor($root);
    }
}
