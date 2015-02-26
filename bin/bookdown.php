<?php
error_reporting(E_ALL);
require dirname(__DIR__) . '/vendor/autoload.php';
$builder = new Bookdown\Bookdown\Builder();
$command = $builder->newCommand($GLOBALS);
exit($command());
