<?php
namespace Bookdown\Bookdown\Process\Conversion;

use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use Psr\Log\LoggerInterface;
use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use Bookdown\Bookdown\Process\ProcessBuilderInterface;

class ConversionProcessBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config, LoggerInterface $logger, Fsio $fsio)
    {
        return new ConversionProcess(
            $logger,
            $fsio,
            $this->newCommonMarkConverter($config)
        );
    }

    protected function newCommonMarkConverter(RootConfig $config)
    {
        $environment = Environment::createCommonMarkEnvironment();

        foreach ($config->getCommonMarkExtensions() as $extension) {
            if (!class_exists($extension)) {
                throw new \RuntimeException(
                    'CommonMark extension class "%s" does not exists. You must use a FCQN!'
                );
            }
            $environment->addExtension(new $extension());
        }

        return new \League\CommonMark\Converter(new DocParser($environment), new HtmlRenderer($environment));
    }
}
