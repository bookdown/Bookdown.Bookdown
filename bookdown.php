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

$processors = array(
    new Bookdown\Content\HtmlProcessor(
        new League\CommonMark\CommonMarkConverter()
    )
);
$processor = new Bookdown\Content\Processor($processors);

$processor($contentList);
