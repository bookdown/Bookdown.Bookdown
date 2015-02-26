<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;

class RootConfig extends Config
{
    protected $target;
    protected $converterBuilder;
    protected $templateBuilder;
    protected $templates = array();
    protected $templateName;

    protected function init()
    {
        parent::init();
        $this->initTarget();
        $this->initTemplates();
        $this->initTemplateName();
        $this->initConverterBuilder();
        $this->initTemplateBuilder();
    }

    protected function initTarget()
    {
        $target = isset($this->json->target)
            ? $this->json->target
            : '_site';

        $target = rtrim($this->fixPath($target), DIRECTORY_SEPARATOR);
        $this->target = $target . DIRECTORY_SEPARATOR;
    }

    protected function initTemplates()
    {
        $this->templates = isset($this->json->templates)
            ? (array) $this->json->templates
            : array();
    }

    protected function initTemplateName()
    {
        $this->templateName = isset($this->json->templateName)
            ? $this->json->templateName
            : null;
    }

    protected function initConverterBuilder()
    {
        $this->converterBuilder = isset($this->json->converterBuilder)
            ? $this->json->converterBuilder
            : 'Bookdown\Bookdown\Converter\ConverterBuilder';
    }

    protected function initTemplateBuilder()
    {
        $this->templateBuilder = isset($this->json->templateBuilder)
            ? $this->json->templateBuilder
            : 'Bookdown\Bookdown\Template\TemplateBuilder';
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

    public function getTemplates()
    {
        return $this->templates;
    }

    public function getTemplateName()
    {
        return $this->templateName;
    }

    public function get($key, $alt = null)
    {
        if (isset($this->json->$key)) {
            return $this->json->$key;
        }
        return $alt;
    }
}
