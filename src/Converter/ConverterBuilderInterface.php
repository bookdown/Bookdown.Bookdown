<?php
namespace Bookdown\Bookdown\Converter;

use Bookdown\Bookdown\Config\RootConfig;

interface ConverterBuilderInterface
{
    public function newInstance(RootConfig $config);
}
