<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;
use Bookdown\Bookdown\Fsio;

class ConfigBuilder
{
    public function __construct(Fsio $fsio)
    {
        $this->fsio = $fsio;
    }

    public function newConfig($file)
    {
        $data = $this->fsio->get($file);
        return new Config($file, $data);
    }

    public function newRootConfig($file)
    {
        $data = $this->fsio->get($file);
        return new RootConfig($file, $data);
    }
}
