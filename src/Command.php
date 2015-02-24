<?php
namespace Bookdown\Content;

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

        $contentList = new ContentList(new ContentFactory(), $target);
        $contentList($origin);

        $processor = new Processor(array(

            // basic HTML conversion
            new HtmlProcessor(new CommonMarkConverter()),

            // extract and number headings
            new HeadingsProcessor(),

            // add TOC pages
            new TocProcessor(),

            // add nav header and footer
            new NavProcessor(),

            // final layout
            new LayoutProcessor(),

        ));

        $processor($contentList);
    }
}
