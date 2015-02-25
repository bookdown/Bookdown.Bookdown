<?php
namespace Bookdown\Bookdown\Template;

use Bookdown\Bookdown\Config\RootConfig;

interface TemplateBuilderInterface
{
    public function newInstance(RootConfig $config);
}
