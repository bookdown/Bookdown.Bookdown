<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Service;

use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Exception;
use Bookdown\Bookdown\Fsio;

/**
 *
 *
 *
 * @package bookdown/bookdown
 *
 */
class ProcessorBuilder
{
    protected $logger;
    protected $fsio;

    public function __construct(LoggerInterface $logger, Fsio $fsio)
    {
        $this->logger = $logger;
        $this->fsio = $fsio;
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
        return $builder->newInstance($config, $this->logger, $this->fsio);
    }
}
