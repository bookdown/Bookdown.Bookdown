<?php
namespace Bookdown\Bookdown\Process;

use Bookdown\Bookdown\Config\RootConfig;
use Bookdown\Bookdown\Fsio;
use League\CommonMark\CommonMarkConverter;

class ConversionBuilder implements ProcessBuilderInterface
{
    public function newInstance(RootConfig $config)
    {
        return new Conversion(
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
