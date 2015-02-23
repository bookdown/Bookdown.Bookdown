<?php
$origin = __DIR__ . '/book/_bookdown.json';
$target = __DIR__ . '/html';

// -----------------------------------------------------------------------------

error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

$contentList = new Bookdown\Content\ContentList(
    new Bookdown\Content\ContentFactory,
    $target
);
$contentList->fill($origin);

$processor = new Bookdown\Content\Processor(array(
    // basic HTML conversion
    new Bookdown\Content\HtmlProcessor(
        new League\CommonMark\CommonMarkConverter()
    ),
    // extract and number headings
    new Bookdown\Content\HeadingsProcessor(),
    // add TOC pages
    new Bookdown\Content\TocProcessor(),
    // add nav header and footer
    new Bookdown\Content\NavProcessor(),
));

$processor($contentList);
