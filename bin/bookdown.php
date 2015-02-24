<?php
error_reporting(E_ALL);
require dirname(__DIR__) . '/vendor/autoload.php';
try {
    $command = new Bookdown\Content\Command();
    $command($_SERVER);
    exit(0);
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
    exit(1);
}
