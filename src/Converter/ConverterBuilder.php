<?php
namespace Bookdown\Bookdown\Converter;

use Bookdown\Bookdown\Config\RootConfig;
use League\CommonMark\CommonMarkConverter;

class ConverterBuilder implements ConverterBuilderInterface
{
    public function newInstance(RootConfig $config)
    {
        return new Converter($this->newCommonMarkConverter($config));
    }

    protected function newCommonMarkConverter(RootConfig $config)
    {
        return new CommonMarkConverter();
    }
}
