<?php
error_reporting(E_ALL);
$autoload = './vendor/autoload.php';
if (! file_exists($autoload)) {
    echo "{$autoload} not found" . PHP_EOL;
    echo "Please install and update Composer before continuing." . PHP_EOL;
    exit(1);
}
require $autoload;
