<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;
use Bookdown\Bookdown\Fsio;

/**
 *
 *
 *
 * @package bookdown/bookdown
 *
 */
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
