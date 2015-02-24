<?php
namespace Bookdown\Content;

use League\CommonMark\CommonMarkConverter;

class Command
{
    public function __invoke($origin, $target)
    {
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
