<?php
$origin = __DIR__ . '/book/_bookdown.json';

// -----------------------------------------------------------------------------

error_reporting(E_ALL);
spl_autoload_register(function ($class) {
    $ns = 'Bookdown\\Content\\';
    $len = strlen($ns);
    if (substr($class, 0, $len) != $ns) {
        return;
    }
    $file = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, $len));
    require __DIR__ . "/src/{$file}.php";
});

$contentList = new Bookdown\Content\ContentList();
$contentListBuilder = new Bookdown\Content\ContentListBuilder(
    $contentList,
    new Bookdown\Content\ContentFactory
);

$contentListBuilder($origin);

$items = $contentList->getItems();
$root = array_shift($items);
foreach ($items as $item) {
    $pad = str_pad('', ($item->getDepth() - 1) * 4);
    echo $pad . $item->getCount() . '. '
        . $item->getTitle() . ': '
        . $item->getAbsoluteHref() . PHP_EOL;
}
