<?php
namespace Bookdown\Bookdown\Service;

use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use Psr\Log\LoggerInterface;

class AssetManager
{
    protected $assets = array();

    /** @var LoggerInterface */
    protected $logger;

    /** @var Fsio */
    protected $fsio;

    /**
     * AssetManager constructor.
     */
    public function __construct(LoggerInterface $logger, Fsio $fsio)
    {
        $this->logger = $logger;
        $this->fsio = $fsio;
    }

    public function addFile($file, $target = null)
    {
        if(is_null($target))
            $target = basename($file);

        if(!$this->assetExists($target)) {
            $this->logger->info("    Adding asset '{$file}' as '{$target}'");
            $this->setAsset($target, $file);
        }
    }

    public function setAsset($name, $source)
    {
        $this->assets[$name] = $source;
    }

    public function assetExists($name)
    {
        return isset($this->assets[$name]);
    }

    public function flush(RootConfig $config)
    {
        $this->logger->info("  Flushing assets");

        foreach($this->assets as $name => $source) {
            $this->logger->info("    $source => $name");

            $this->fsio->put($config->getTarget() . DIRECTORY_SEPARATOR . $name, $this->fsio->get(dirname($config->getTemplate()) . DIRECTORY_SEPARATOR . $source));
        }
    }
}