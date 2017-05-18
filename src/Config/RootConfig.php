<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;

/**
 *
 * A special root-level index configuration.
 *
 * @package bookdown/bookdown
 *
 */
class RootConfig extends IndexConfig
{
    /**
     *
     * The target directory for Bookdown output.
     *
     * @var string
     *
     */
    protected $target;

    /**
     *
     * The conversion-process builder class name.
     *
     * @var string
     *
     */
    protected $conversionProcess;

    /**
     *
     * The rendering-process builder class name.
     *
     * @var string
     *
     */
    protected $renderingProcess;

    /**
     *
     * The index-process builder class name.
     *
     * @var string
     *
     */
    protected $indexProcess;

    /**
     *
     * The toc-process builder class name.
     *
     * @var string
     *
     */
    protected $tocProcess;

    /**
     *
     * The headings-process builder class name.
     *
     * @var string
     *
     */
    protected $headingsProcess;

    /**
     *
     * The copy-image-process builder class name.
     *
     * @var string
     *
     */
    protected $copyImageProcess;

    /**
     *
     * The copyright-process builder class name.
     *
     * @var string
     *
     */
    protected $copyrightProcess;

    /**
     *
     * The path to the master output template.
     *
     * @var string
     *
     */
    protected $template;

    /**
     *
     * The root for generated HREF links.
     *
     * @var string
     *
     */
    protected $rootHref;

    /**
     *
     * The copyright text.
     *
     * @var string
     *
     */
    protected $copyright;

    /**
     *
     * The type of TOC numbering to use.
     *
     * @var string
     *
     */
    protected $numbering;

    /**
     *
     * An array of CommonMark extension class names.
     *
     * @var array
     *
     */
    protected $commonMarkExtensions = [];

    /**
     *
     * Sets all override values from the command-line options.
     *
     * @param array $overrides The override values.
     *
     */
    public function setOverrides(array $overrides)
    {
        foreach ($overrides as $key => $val) {
            $this->setOverride($key, $val);
        }
    }

    /**
     *
     * Sets one override value.
     *
     * @param string $key The config element to override.
     *
     * @param string $val The override value.
     *
     */
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

    /**
     *
     * Initializes this config object.
     *
     */
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

    /**
     *
     * Initializes the $target property.
     *
     * @throws Exception on error.
     *
     */
    protected function initTarget()
    {
        if (empty($this->json->target)) {
            throw new Exception("No target set in '{$this->file}'.");
        }

        $target = $this->json->target;
        $target = rtrim($this->fixPath($target), DIRECTORY_SEPARATOR);
        $this->target = $target . DIRECTORY_SEPARATOR;
    }

    /**
     *
     * Initializes the $rootHref property.
     *
     */
    protected function initRootHref()
    {
        $this->rootHref = empty($this->json->rootHref)
            ? '/'
            : $this->json->rootHref;
    }

    /**
     *
     * Initializes the $commonMarkExtensions property.
     *
     * @throws Exception on error.
     *
     */
    protected function initCommonMarkExtensions()
    {
        if (empty($this->json->extensions)
            || empty($this->json->extensions->commonmark)
        ) {
            return;
        }

        if (! is_array($this->json->extensions->commonmark)) {
            throw new \InvalidArgumentException(
                sprintf('The extension parameter "commonmark" must be of type "array".')
            );
        }

        foreach ($this->json->extensions->commonmark as $extension) {
            $this->commonMarkExtensions[] = $extension;
        }
    }

    /**
     *
     * Initializes the $template property.
     *
     */
    protected function initTemplate()
    {
        $this->template = empty($this->json->template)
            ? null
            : $this->fixPath($this->json->template);
    }

    /**
     *
     * Initializes the $copyright property.
     *
     */
    protected function initCopyright()
    {
        $this->copyright = empty($this->json->copyright)
            ? ''
            : $this->json->copyright;
    }

    /**
     *
     * Initializes the $numbering property.
     *
     */
    protected function initNumbering()
    {
        $this->numbering = !isset($this->json->numbering)
            ? 'decimal'
            : $this->json->numbering;
    }

    /**
     *
     * Initializes the $conversionProcess property.
     *
     */
    protected function initConversionProcess()
    {
        $this->conversionProcess = empty($this->json->conversionProcess)
            ? 'Bookdown\Bookdown\Process\Conversion\ConversionProcessBuilder'
            : $this->json->conversionProcess;
    }

    /**
     *
     * Initializes the $headingsProcess property.
     *
     */
    protected function initHeadingsProcess()
    {
        $this->headingsProcess = empty($this->json->headingsProcess)
            ? 'Bookdown\Bookdown\Process\Headings\HeadingsProcessBuilder'
            : $this->json->headingsProcess;
    }

    /**
     *
     * Initializes the $copyImageProcess property.
     *
     */
    protected function initCopyImageProcess()
    {
        $this->copyImageProcess = empty($this->json->copyImageProcess)
            ? 'Bookdown\Bookdown\Process\Resource\CopyImageProcessBuilder'
            : $this->json->copyImageProcess;
    }

    /**
     *
     * Initializes the $copyrightProcess property.
     *
     */
    protected function initCopyrightProcess()
    {
        $this->copyrightProcess = empty($this->json->copyrightProcess)
            ? 'Bookdown\Bookdown\Process\Info\CopyrightProcessBuilder'
            : $this->json->copyrightProcess;
    }

    /**
     *
     * Initializes the $tocProcess property.
     *
     */
    protected function initTocProcess()
    {
        $this->tocProcess = empty($this->json->tocProcess)
            ? 'Bookdown\Bookdown\Process\Toc\TocProcessBuilder'
            : $this->json->tocProcess;
    }

    /**
     *
     * Initializes the $renderingProcess property.
     *
     */
    protected function initRenderingProcess()
    {
        $this->renderingProcess = empty($this->json->renderingProcess)
            ? 'Bookdown\Bookdown\Process\Rendering\RenderingProcessBuilder'
            : $this->json->renderingProcess;
    }

    /**
     *
     * Initializes the $indexProcess property.
     *
     */
    protected function initIndexProcess()
    {
        $this->indexProcess = empty($this->json->indexProcess)
            ? 'Bookdown\Bookdown\Process\Index\IndexProcessBuilder'
            : $this->json->indexProcess;
    }

    /**
     *
     * Returns the conversion process builder class name.
     *
     * @return string
     *
     */
    public function getConversionProcess()
    {
        return $this->conversionProcess;
    }

    /**
     *
     * Returns the headings process builder class name.
     *
     * @return string
     *
     */
    public function getHeadingsProcess()
    {
        return $this->headingsProcess;
    }

    /**
     *
     * Returns the copy-image process builder class name.
     *
     * @return string
     *
     */
    public function getCopyImageProcess()
    {
        return $this->copyImageProcess;
    }

    /**
     *
     * Returns the copyright process builder class name.
     *
     * @return string
     *
     */
    public function getCopyrightProcess()
    {
        return $this->copyrightProcess;
    }

    /**
     *
     * Returns the TOC process builder class name.
     *
     * @return string
     *
     */
    public function getTocProcess()
    {
        return $this->tocProcess;
    }

    /**
     *
     * Returns the rendering process builder class name.
     *
     * @return string
     *
     */
    public function getRenderingProcess()
    {
        return $this->renderingProcess;
    }

    /**
     *
     * Returns the index process builder class name.
     *
     * @return string
     *
     */
    public function getIndexProcess()
    {
        return $this->indexProcess;
    }

    /**
     *
     * Returns the target directory for Bookdown output.
     *
     * @return string
     *
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     *
     * Returns the root HREF for links.
     *
     * @return string
     *
     */
    public function getRootHref()
    {
        return $this->rootHref;
    }

    /**
     *
     * Returns the master template path.
     *
     * @return string
     *
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     *
     * Returns the TOC depth value.
     *
     * @return string
     *
     */
    public function getTocDepth()
    {
        return $this->tocDepth;
    }

    /**
     *
     * Returns the copyright string.
     *
     * @return string
     *
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     *
     * Returns the TOC numbering style.
     *
     * @return string
     *
     */
    public function getNumbering()
    {
        return $this->numbering;
    }

    /**
     *
     * Returns a value from the JSON object, or an alternate default value.
     *
     * @param string $key The JSON key.
     *
     * @param mixed $alt The default to use when the key does not exist.
     *
     * @return mixed
     *
     */
    public function get($key, $alt = null)
    {
        if (isset($this->json->$key)) {
            return $this->json->$key;
        }
        return $alt;
    }

    /**
     *
     * Returns the array of CommonMark extension class names.
     *
     * @return array
     *
     */
    public function getCommonMarkExtensions()
    {
        return $this->commonMarkExtensions;
    }
}
