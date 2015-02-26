<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;

class RootConfig extends Config
{
    protected $target;
    protected $conversionProcess;
    protected $renderingProcess;
    protected $tocProcess;
    protected $headingsProcess;
    protected $templates = array();
    protected $templateName;

    protected function init()
    {
        parent::init();
        $this->initTarget();
        $this->initTemplates();
        $this->initTemplateName();
        $this->initConversionProcess();
        $this->initHeadingsProcess();
        $this->initTocProcess();
        $this->initRenderingProcess();
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
        $this->templates = empty($this->json->templates)
            ? array()
            : (array) $this->json->templates;
    }

    protected function initTemplateName()
    {
        $this->templateName = empty($this->json->templateName)
            ? null
            : $this->json->templateName;
    }

    protected function initConversionProcess()
    {
        $this->conversionProcess = empty($this->json->conversionProcess)
            ? 'Bookdown\Bookdown\Process\Conversion\ConversionProcessBuilder'
            : $this->json->conversionProcess;
    }

    protected function initHeadingsProcess()
    {
        $this->headingsProcess = empty($this->json->headingsProcess)
            ? 'Bookdown\Bookdown\Process\Headings\HeadingsProcessBuilder'
            : $this->json->headingsProcess;
    }

    protected function initTocProcess()
    {
        $this->tocProcess = empty($this->json->tocProcess)
            ? 'Bookdown\Bookdown\Process\Toc\TocProcessBuilder'
            : $this->json->tocProcess;
    }

    protected function initRenderingProcess()
    {
        $this->renderingProcess = empty($this->json->renderingProcess)
            ? 'Bookdown\Bookdown\Process\Rendering\RenderingProcessBuilder'
            : $this->json->renderingProcess;
    }

    public function getConversionProcess()
    {
        return $this->conversionProcess;
    }

    public function getHeadingsProcess()
    {
        return $this->headingsProcess;
    }

    public function getTocProcess()
    {
        return $this->tocProcess;
    }

    public function getRenderingProcess()
    {
        return $this->renderingProcess;
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
