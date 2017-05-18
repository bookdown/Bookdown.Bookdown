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
 * A factory for configuration objects.
 *
 * @package bookdown/bookdown
 *
 */
class ConfigFactory
{
    /**
     *
     * An array of root-level configuration overrides provided as command-line
     * options.
     *
     * @var array
     *
     */
    protected $rootConfigOverrides = [];

    /**
     *
     * Sets the root-level configuration overrides provided as command-line
     * options.
     *
     * @param array array $rootConfigOverrides The override values.
     *
     */
    public function setRootConfigOverrides(array $rootConfigOverrides)
    {
        $this->rootConfigOverrides = $rootConfigOverrides;
    }

    /**
     *
     * Returns a new index-level config object.
     *
     * @param string $file The path of the configuration file.
     *
     * @param string $data The contents of the configuration file.
     *
     * @return IndexConfig
     *
     */
    public function newIndexConfig($file, $data)
    {
        return new IndexConfig($file, $data);
    }

    /**
     *
     * Returns a new root-level config object.
     *
     * @param string $file The path of the configuration file.
     *
     * @param string $data The contents of the configuration file.
     *
     * @return RootConfig
     *
     */
    public function newRootConfig($file, $data)
    {
        $config = new RootConfig($file, $data);
        $config->setOverrides($this->rootConfigOverrides);
        return $config;
    }
}
