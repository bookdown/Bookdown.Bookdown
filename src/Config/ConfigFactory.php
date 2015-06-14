<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;
use Bookdown\Bookdown\Fsio;

class ConfigFactory
{
    protected $rootConfigOverrides = array();

    public function setRootConfigOverrides(array $rootConfigOverrides)
    {
        $this->rootConfigOverrides = $rootConfigOverrides;
    }

    public function newIndexConfig($file, $data)
    {
        return new IndexConfig($file, $data);
    }

    public function newRootConfig($file, $data)
    {
        $config = new RootConfig($file, $data);
        $config->setOverrides($this->rootConfigOverrides);
        return $config;
    }
}
