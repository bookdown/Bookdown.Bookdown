<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;

class RootConfig extends Config
{
    protected $template;
    protected $target;

    protected function init()
    {
        parent::init();
        $this->initTarget();
        $this->initTemplate();
    }

    protected function initTarget()
    {
        if (! isset($this->json->target)) {
            throw new Exception("No target specified in '{$this->file}'.");
        }

        $this->target = rtrim($this->fixPath($this->json->target), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    protected function initTemplate()
    {
        if (! isset($this->json->template)) {
            throw new Exception("No template listed in '{$this->file}'.");
        }

        $this->template = $this->fixPath($this->json->template);
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getTarget()
    {
        return $this->target;
    }
}
