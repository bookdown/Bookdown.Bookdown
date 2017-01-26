<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;

class RootConfig extends IndexConfig
{
    protected $target;
    protected $conversionProcess;
    protected $renderingProcess;
    protected $indexProcess;
    protected $tocProcess;
    protected $headingsProcess;
    protected $copyImageProcess;
    protected $copyrightProcess;
    protected $template;
    protected $rootHref;
    protected $tocDepth;
    protected $copyright;
    protected $numbering;

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
        $this->initCopyright();
        $this->initConversionProcess();
        $this->initHeadingsProcess();
        $this->initCopyImageProcess();
        $this->initTocProcess();
        $this->initRenderingProcess();
        $this->initIndexProcess();
        $this->initCopyrightProcess();
        $this->initNumbering();
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

    protected function initCopyright()
    {
        $this->copyright = empty($this->json->copyright)
            ? ''
            : $this->json->copyright;
    }

    protected function initNumbering()
    {
        $this->numbering = !isset($this->json->numbering)
            ? 'decimal'
            : $this->json->numbering;
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

    protected function initCopyrightProcess()
    {
        $this->copyrightProcess = empty($this->json->copyrightProcess)
            ? 'Bookdown\Bookdown\Process\Info\CopyrightProcessBuilder'
            : $this->json->copyrightProcess;
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

    protected function initIndexProcess()
    {
        $this->indexProcess = empty($this->json->indexProcess)
            ? 'Bookdown\Bookdown\Process\Index\IndexProcessBuilder'
            : $this->json->indexProcess;
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

    public function getCopyrightProcess()
    {
        return $this->copyrightProcess;
    }

    public function getTocProcess()
    {
        return $this->tocProcess;
    }

    public function getRenderingProcess()
    {
        return $this->renderingProcess;
    }

    public function getIndexProcess()
    {
        return $this->indexProcess;
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

    public function getCopyright()
    {
        return $this->copyright;
    }

    public function getNumbering()
    {
        return $this->numbering;
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
