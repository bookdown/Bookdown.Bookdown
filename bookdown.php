<?php
$origin = __DIR__ . '/book/_bookdown.json';
$target = __DIR__ . '/html';

// -----------------------------------------------------------------------------

error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

$contentList = new Bookdown\Content\ContentList(new Bookdown\Content\ContentFactory);
$contentList->fill($origin);

$processor = new Bookdown\Content\Processor(
    new League\CommonMark\CommonMarkConverter()
);

$processor($contentList, $target);
