<?php
namespace Bookdown\Content;

use League\CommonMark\CommonMarkConverter;
use Aura\View\ViewFactory;
use Aura\Html\HelperLocatorFactory;

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

        $ContentCollector = new ContentCollector(new ContentFactory(), $target);
        $root = $ContentCollector($origin);

        $helpersFactory = new HelperLocatorFactory();
        $helpers = $helpersFactory->newInstance();

        $viewFactory = new ViewFactory();
        $view = $viewFactory->newInstance($helpers);

        $templatesDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates';
        $templates = array(
            'default' => "{$templatesDir}/default.php",
            'navheader' => "{$templatesDir}/navheader.php",
            'navfooter' => "{$templatesDir}/navfooter.php",
            'toc' => "{$templatesDir}/toc.php",
        );

        $processor = new Processor(array(

            // basic HTML conversion
            new HtmlProcessor(new CommonMarkConverter()),

            // extract and number headings
            new HeadingsProcessor(new HeadingFactory()),

            // add TOC entries
            new TocProcessor(),

            // final layout
            new LayoutProcessor($view, $templates),
        ));

        $processor($root);
    }
}
