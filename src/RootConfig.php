<?php
namespace Bookdown\Bookdown;

class RootConfig extends Config
{
    protected $templates = array();

    protected function init()
    {
        parent::init();
        $this->initTemplates();
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
}
