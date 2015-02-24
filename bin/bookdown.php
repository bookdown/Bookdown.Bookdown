<?php
error_reporting(E_ALL);
require dirname(__DIR__) . '/vendor/autoload.php';

if (! isset($_SERVER['argv'][1])) {
    echo "Please enter an origin bookdown.json file as the first argument." . PHP_EOL;
    exit(1);
}
$origin = $_SERVER['argv'][1];

if (! isset($_SERVER['argv'][2])) {
    echo "Please enter a writable target directory as the second argument." . PHP_EOL;
    exit(1);
}
$target = $_SERVER['argv'][2];

$command = new Bookdown\Content\Command();
$command($origin, $target);
exit(0);
