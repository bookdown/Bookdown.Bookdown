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
 * Builds the Processor object.
 *
 * @package bookdown/bookdown
 *
 */
class ProcessorBuilder
{
    /**
     *
     * A logger implementation.
     *
     * @var LoggerInterface
     *
     */
    protected $logger;

    /**
     *
     * A filesystem I/O object.
     *
     * @var Fsio
     *
     */
    protected $fsio;

    /**
     *
     * Constructor.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param Fsio $fsio A filesystem I/O object.
     *
     */
    public function __construct(LoggerInterface $logger, Fsio $fsio)
    {
        $this->logger = $logger;
        $this->fsio = $fsio;
    }

    /**
     *
     * Returns a new Processor object.
     *
     * @param RootConfig $config The root-level config object.
     *
     * @return Processor
     *
     */
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

    /**
     *
     * Returns a new Process object.
     *
     * @param RootConfig $config The root-level config object.
     *
     * @param string $name The process name.
     *
     * @return ProcessInterface
     *
     */
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
