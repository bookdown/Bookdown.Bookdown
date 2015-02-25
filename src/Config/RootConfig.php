<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;

class RootConfig extends Config
{
    protected $target;
    protected $templateBuilder;

    protected function init()
    {
        parent::init();
        $this->initTarget();
        $this->initTemplateBuilder();
    }

    protected function initTarget()
    {
        if (! isset($this->json->target)) {
            throw new Exception("No target specified in '{$this->file}'.");
        }

        $this->target = rtrim($this->fixPath($this->json->target), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    protected function initTemplateBuilder()
    {
        if (! isset($this->json->templateBuilder)) {
            $this->templateBuilder = 'Bookdown\Bookdown\Template\TemplateBuilder';
            return;
        }

        $this->templateFactory = $this->json->templateBuilder;
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
