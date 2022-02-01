<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Process\Conversion;

use League\CommonMark\MarkdownConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

/**
 *
 * Builds a ConversionProcess object.
 *
 * @package bookdown/bookdown
 *
 */
class ConversionProcessBuilder implements ProcessBuilderInterface
{
    /**
     *
     * Returns a new ConversionProcess object.
     *
     * @param RootConfig $config The root-level config object.
     *
     * @param LoggerInterface $logger A logger implementation.
     *
     * @param Fsio $fsio A filesystem I/O object.
     *
     * @return ConversionProcess
     *
     */
    public function newInstance(RootConfig $config, LoggerInterface $logger, Fsio $fsio)
    {
        return new ConversionProcess(
            $logger,
            $fsio,
            $this->newCommonMarkConverter($config)
        );
    }

    /**
     *
     * Returns a new CommonMarkConverter object.
     *
     * @param RootConfig $config The root-level config object.
     *
     * @return Converter
     *
     * @throws \RuntimeException when a requested CommonMark extension class
     * does not exist.
     *
     */
    protected function newCommonMarkConverter(RootConfig $config)
    {
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $extensions = $config->getCommonMarkExtensions();

        foreach ($extensions as $extension) {
            if (! class_exists($extension)) {
                throw new \RuntimeException(
                    sprintf('CommonMark extension class "%s" does not exists. You must use a FCQN!', $extension)
                );
            }
            $environment->addExtension(new $extension());
        }

        return new MarkdownConverter($environment);
    }
}
