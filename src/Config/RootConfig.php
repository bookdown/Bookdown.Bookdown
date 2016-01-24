<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;

class RootConfig extends IndexConfig
{
    protected $target;
    protected $conversionProcess;
    protected $renderingProcess;
    protected $tocProcess;
    protected $headingsProcess;
    protected $copyImageProcess;
    protected $template;
    protected $rootHref;
    protected $tocDepth;
    protected $menuProcess;

    /**
     * @var array
     */
    protected $commonMarkExtensions = array();

    public function setOverrides(array $overrides)
    {
        foreach ($overrides as $key => $val) {
            $this->setOverride($key, $val);
        }
    }

    protected function setOverride($key, $val)
    {
        $val = trim($val);
        if (! $val) {
            return;
        }

        $key = ltrim($key, '-');

        if ($key === 'template') {
            $this->template = $val;
            return;
        }

        if ($key === 'target') {
            $this->target = rtrim($val, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            return;
        }

        if ($key === 'root-href') {
            $this->rootHref = $val;
            return;
        }
    }

    protected function init()
    {
        parent::init();
        $this->initTarget();
        $this->initRootHref();
        $this->initCommonMarkExtensions();
        $this->initTemplate();
        $this->initTocDepth();
        $this->initConversionProcess();
        $this->initHeadingsProcess();
        $this->initCopyImageProcess();
        $this->initTocProcess();
        $this->initMenuProcess();
        $this->initRenderingProcess();
    }

    protected function initTarget()
    {
        if (empty($this->json->target)) {
            throw new Exception("No target set in '{$this->file}'.");
        }

        $target = $this->json->target;
        $target = rtrim($this->fixPath($target), DIRECTORY_SEPARATOR);
        $this->target = $target . DIRECTORY_SEPARATOR;
    }

    protected function initRootHref()
    {
        $this->rootHref = empty($this->json->rootHref)
            ? '/'
            : $this->json->rootHref;
    }

    protected function initCommonMarkExtensions()
    {
        if (empty($this->json->extensions)
            || empty($this->json->extensions->commonmark)
        ) {
            return;
        }

        if (!is_array($this->json->extensions->commonmark)) {
            throw new \InvalidArgumentException(
                sprintf('The extension parameter "commonmark" must be of type "array".')
            );
        }

        foreach ($this->json->extensions->commonmark as $extension) {
            $this->commonMarkExtensions[] = $extension;
        }
    }

    protected function initTemplate()
    {
        $this->template = empty($this->json->template)
            ? null
            : $this->fixPath($this->json->template);
    }

    protected function initTocDepth()
    {
        $this->tocDepth = empty($this->json->tocDepth)
            ? 0
            : (int) $this->json->tocDepth;
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

    protected function initCopyImageProcess()
    {
        $this->copyImageProcess = empty($this->json->copyImageProcess)
            ? 'Bookdown\Bookdown\Process\Resource\CopyImageProcessBuilder'
            : $this->json->copyImageProcess;
    }

    protected function initTocProcess()
    {
        $this->tocProcess = empty($this->json->tocProcess)
            ? 'Bookdown\Bookdown\Process\Toc\TocProcessBuilder'
            : $this->json->tocProcess;
    }

    protected function initMenuProcess()
    {
        $this->menuProcess = empty($this->json->menuProcess)
            ? 'Bookdown\Bookdown\Process\Navigation\MenuProcessBuilder'
            : $this->json->menuProcess;
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

    public function getCopyImageProcess()
    {
        return $this->copyImageProcess;
    }

    public function getTocProcess()
    {
        return $this->tocProcess;
    }

    public function getMenuProcess()
    {
        return $this->menuProcess;
    }

    public function getRenderingProcess()
    {
        return $this->renderingProcess;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function getRootHref()
    {
        return $this->rootHref;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getTocDepth()
    {
        return $this->tocDepth;
    }

    public function get($key, $alt = null)
    {
        if (isset($this->json->$key)) {
            return $this->json->$key;
        }
        return $alt;
    }

    /**
     * @return array
     */
    public function getCommonMarkExtensions()
    {
        return $this->commonMarkExtensions;
    }
}
