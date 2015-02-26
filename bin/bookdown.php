<?php
error_reporting(E_ALL);
require dirname(__DIR__) . '/vendor/autoload.php';
$commandBuilder = new Bookdown\Bookdown\CommandBuilder();
$command = $commandBuilder->newInstance($GLOBALS);
exit($command());
