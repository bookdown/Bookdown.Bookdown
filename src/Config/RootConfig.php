<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;

class RootConfig extends Config
{
    protected $target;
    protected $converterBuilder;
    protected $templateBuilder;

    protected function init()
    {
        parent::init();
        $this->initTarget();
        $this->initConverterBuilder();
        $this->initTemplateBuilder();
    }

    protected function initTarget()
    {
        $target = '_site';
        if (isset($this->json->target)) {
            $target = $this->json->target;
        }

        $this->target = rtrim($this->fixPath($target), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    protected function initConverterBuilder()
    {
        if (! isset($this->json->converterBuilder)) {
            $this->converterBuilder = 'Bookdown\Bookdown\Converter\ConverterBuilder';
            return;
        }

        $this->converterBuilder = $this->json->converterBuilder;
    }

    protected function initTemplateBuilder()
    {
        if (! isset($this->json->templateBuilder)) {
            $this->templateBuilder = 'Bookdown\Bookdown\Template\TemplateBuilder';
            return;
        }

        $this->templateBuilder = $this->json->templateBuilder;
    }

    public function getConverterBuilder()
    {
        return $this->converterBuilder;
    }

    public function getTemplateBuilder()
    {
        return $this->templateBuilder;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function get($key)
    {
        if (isset($this->json->$key)) {
            return $this->json->$key;
        }
    }
}
