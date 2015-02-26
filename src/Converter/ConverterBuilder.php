<?php
namespace Bookdown\Bookdown\Converter;

use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use League\CommonMark\CommonMarkConverter;

class ConverterBuilder implements ConverterBuilderInterface
{
    public function newInstance(RootConfig $config)
    {
        return new Converter(
            $this->newFsio(),
            $this->newCommonMarkConverter()
        );
    }

    protected function newCommonMarkConverter()
    {
        return new CommonMarkConverter();
    }

    protected function newFsio()
    {
        return new Fsio();
    }
}
