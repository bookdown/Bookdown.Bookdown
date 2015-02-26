<?php
error_reporting(E_ALL);
$file = './vendor/autoload.php';
if (! file_exists($file)) {
    echo "{$file} not found" . PHP_EOL;
    echo "Try 'composer update' before continuing." . PHP_EOL;
    exit(1);
}
require $file;
