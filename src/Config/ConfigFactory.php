<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;
use Bookdown\Bookdown\Fsio;

class ConfigFactory
{
    public function newIndexConfig($file, $data)
    {
        return new IndexConfig($file, $data);
    }

    public function newRootConfig($file, $data)
    {
        return new RootConfig($file, $data);
    }
}
