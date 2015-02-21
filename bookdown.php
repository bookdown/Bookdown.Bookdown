<?php
$origin = __DIR__ . '/book/_bookdown.json';

// -----------------------------------------------------------------------------

error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

$contentList = new Bookdown\Content\ContentList(new Bookdown\Content\ContentFactory);
$contentList->fill($origin);

$items = $contentList->getItems();
$root = array_shift($items);
foreach ($items as $item) {
    $pad = str_pad('', ($item->getDepth() - 1) * 4);
    echo $pad . $item->getNumber() . ' '
        . $item->getTitle() . ': '
        . $item->getTargetFile() . PHP_EOL;
}
