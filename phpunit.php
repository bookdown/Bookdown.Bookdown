<?php
error_reporting(E_ALL);
spl_autoload_register(function ($class) {
    $ns = 'Bookdown\\Content\\';
    $len = strlen($ns);
    if (substr($class, 0, $len) != $ns) {
        return;
    }
    $file = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, $len));
    require $file;
});
