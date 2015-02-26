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

    protected function initConversionProcess()
    {
        $this->conversionProcess = isset($this->json->conversionProcess)
            ? $this->json->conversionProcess
            : 'Bookdown\Bookdown\Process\Conversion\ConversionProcessBuilder';
    }

    protected function initHeadingsProcess()
    {
        $this->headingsProcess = isset($this->json->headingsProcess)
            ? $this->json->headingsProcess
            : 'Bookdown\Bookdown\Process\Headings\HeadingsProcessBuilder';
    }

    protected function initTocProcess()
    {
        $this->tocProcess = isset($this->json->tocProcess)
            ? $this->json->tocProcess
            : 'Bookdown\Bookdown\Process\Toc\TocProcessBuilder';
    }

    protected function initRenderingProcess()
    {
        $this->renderingProcess = isset($this->json->renderingProcess)
            ? $this->json->renderingProcess
            : 'Bookdown\Bookdown\Process\Rendering\RenderingProcessBuilder';
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
