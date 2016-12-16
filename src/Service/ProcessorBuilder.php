<?php
namespace Bookdown\Bookdown\Service;

use Bookdown\Bookdown\Service\AssetManager\AssetManagerAwareInterface;
use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Exception;
use Bookdown\Bookdown\Fsio;

class ProcessorBuilder
{
    protected $logger;
    protected $fsio;
    protected $assetManager;

    public function __construct(LoggerInterface $logger, Fsio $fsio, AssetManager $assetManager)
    {
        $this->logger = $logger;
        $this->fsio = $fsio;
        $this->assetManager = $assetManager;
    }

    public function newProcessor(RootConfig $config)
    {
        return new Processor(
            $this->logger,
            array(
                $this->newProcess($config, 'Conversion'),
                $this->newProcess($config, 'Copyright'),
                $this->newProcess($config, 'Headings'),
                $this->newProcess($config, 'CopyImage'),
                $this->newProcess($config, 'Toc'),
                $this->newProcess($config, 'Rendering'),
                $this->newProcess($config, 'Index'),
            )
        );
    }

    public function newProcess(RootConfig $config, $name)
    {
        $method = "get{$name}Process";
        $class = $config->$method();

        $implemented = is_subclass_of(
            $class,
            'Bookdown\Bookdown\Process\ProcessBuilderInterface'
        );
        if (! $implemented) {
            throw new Exception(
                "'{$class}' does not implement ProcessBuilderInterface"
            );
        }

        $builder = new $class();
        $instance = $builder->newInstance($config, $this->logger, $this->fsio);
        
        if(is_subclass_of($instance, AssetManagerAwareInterface::class))
        {
            $instance->setAssetManager($this->assetManager);
        }
        
        return $instance;
    }
}
