<?php
error_reporting(E_ALL);
require dirname(__DIR__) . '/vendor/autoload.php';
$container = new Bookdown\Bookdown\Container();
$command = $container->newCommand($GLOBALS);
exit($command());
