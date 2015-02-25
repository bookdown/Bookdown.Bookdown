<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;

class RootConfig extends Config
{
    protected $templates = array();
    protected $target;

    protected function init()
    {
        parent::init();
        $this->initTarget();
        $this->initTemplates();
    }

    protected function initTarget()
    {
        if (! isset($this->json->target)) {
            throw new Exception("No target specified in '{$this->file}'.");
        }

        $this->target = rtrim($this->fixPath($this->json->target), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    protected function initTemplates()
    {
        if (! isset($this->json->templates)) {
            throw new Exception("No templates listed in '{$this->file}'.");
        }

        $this->templates = (array) $this->json->templates;
        foreach ($this->templates as $name => $template) {
            $this->templates[$name] = $this->fixPath($template);
        }
    }

    public function getTemplates()
    {
        return $this->templates;
    }

    public function getTarget()
    {
        return $this->target;
    }
}
