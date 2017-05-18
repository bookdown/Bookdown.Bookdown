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
 * An index-level config object.
 *
 * @package bookdown/bookdown
 *
 */
class IndexConfig
{
    /**
     *
     * The path of the config file.
     *
     * @var string
     *
     */
    protected $file;

    /**
     *
     * The directory of the config file.
     *
     * @var string
     *
     */
    protected $dir;

    /**
     *
     * Was the config file retrieved from a remote location?
     *
     * @var bool
     *
     */
    protected $isRemote = false;

    /**
     *
     * The decoded JSON from the config file.
     *
     * @var object
     *
     */
    protected $json;

    /**
     *
     * The "title" value from the config file.
     *
     * @var string
     *
     */
    protected $title;

    /**
     *
     * The "content" values from the config file.
     *
     * @var array
     *
     */
    protected $content;

    /**
     *
     * The "tocDepth" value from the config file.
     *
     * @var int
     *
     */
    protected $tocDepth;

    /**
     *
     * Constructor.
     *
     * @param string $file The path of the config file.
     *
     * @param string $data The contents of the config file.
     *
     */
    public function __construct($file, $data)
    {
        $this->initFile($file);
        $this->initJson($data);
        $this->init();
    }

    /**
     *
     * Initializes this config object.
     *
     */
    protected function init()
    {
        $this->initDir();
        $this->initTitle();
        $this->initContent();
        $this->initTocDepth();
    }

    /**
     *
     * Initializes the $file and $isRemote properties.
     *
     * @param string $file The path to the file.
     *
     */
    protected function initFile($file)
    {
        $this->file = $file;
        $this->isRemote = strpos($file, '://') !== false;
    }

    /**
     *
     * Initializes the $dir property.
     *
     */
    protected function initDir()
    {
        $this->dir = dirname($this->file) . DIRECTORY_SEPARATOR;
    }

    /**
     *
     * Initializes the $json property.
     *
     * @param string $data The contents of the config file.
     *
     * @throws Exception on error.
     *
     */
    protected function initJson($data)
    {
        $this->json = json_decode($data);
        if (! $this->json) {
            throw new Exception("Malformed JSON in '{$this->file}'.");
        }
    }

    /**
     *
     * Initializes the $title property.
     *
     * @throws Exception on error.
     *
     */
    protected function initTitle()
    {
        if (empty($this->json->title)) {
            throw new Exception("No title set in '{$this->file}'.");
        }
        $this->title = $this->json->title;
    }

    /**
     *
     * Initializes the $content property.
     *
     * @throws Exception on error.
     *
     */
    protected function initContent()
    {
        $content = empty($this->json->content)
            ? array()
            : $this->json->content;

        if (! $content) {
            throw new Exception("No content listed in '{$this->file}'.");
        }

        if (! is_array($content)) {
            throw new Exception("Content must be an array in '{$this->file}'.");
        }

        foreach ($content as $key => $val) {
            $this->initContentItem($val);
        }
    }

    /**
     *
     * Initializes a $content property element from an origin location.
     *
     * @param mixed $origin An origin location for a content item. If an object,
     * it's an override filename and a page path; if a string, it's a page path
     * or a bookdown.json file pointing to another page index.
     *
     * @throws Exception on error.
     *
     */
    protected function initContentItem($origin)
    {
        if (is_object($origin)) {
            $spec = (array) $origin;
            $name = key($spec);
            $origin = current($spec);
            return $this->addContent($name, $origin);
        }

        if (! is_string($origin)) {
            throw new Exception("Content origin must be object or string in '{$this->file}'.");
        }

        if (substr($origin, -13) == 'bookdown.json') {
            $name = basename(dirname($origin));
            return $this->addContent($name, $origin);
        }

        $name = basename($origin);
        $pos = strrpos($name, '.');
        if ($pos !== false) {
            $name = substr($name, 0, $pos);
        }
        return $this->addContent($name, $origin);
    }

    /**
     *
     * Initializes the $tocDepth property.
     *
     */
    protected function initTocDepth()
    {
        $this->tocDepth = empty($this->json->tocDepth)
            ? 0
            : (int) $this->json->tocDepth;
    }

    /**
     *
     * Adds a $content item name and path.
     *
     * @param string $name The item name.
     *
     * @param string $origin The origin of the content item.
     *
     * @throws Exception on error.
     *
     */
    protected function addContent($name, $origin)
    {
        if ($name == 'index') {
            throw new Exception("Disallowed 'index' content name in '{$this->file}'.");
        }

        if (isset($this->content[$name])) {
            throw new Exception("Content name '{$name}' already set in '{$this->file}'.");
        }

        $this->content[$name] = $this->fixPath($origin);
    }

    /**
     *
     * Returns a validated and sanitized content origin path.
     *
     * @param string $path The origin path.
     *
     * @return string
     *
     * @throws Exception on error.
     *
     */
    protected function fixPath($path)
    {
        if (strpos($path, '://') !== false) {
            return $path;
        }

        if ($this->isRemote() && $path{0} === DIRECTORY_SEPARATOR) {
            throw new Exception(
                "Cannot handle absolute content path '{$path}' in remote '{$this->file}'."
            );
        }

        if ($path{0} === DIRECTORY_SEPARATOR) {
            return $path;
        }

        return $this->getDir() . ltrim($path, DIRECTORY_SEPARATOR);
    }

    /**
     *
     * Was this config file retrieved from a remote location?
     *
     * @return bool
     *
     */
    public function isRemote()
    {
        return $this->isRemote;
    }

    /**
     *
     * The path to the config file.
     *
     * @return string
     *
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     *
     * The directory of the config file.
     *
     * @return string
     *
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     *
     * The title for the index.
     *
     * @return string
     *
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * The pages (and sub-indexes) for the index.
     *
     * @return array
     *
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     *
     * The TOC depth level for the index.
     *
     * @return string
     *
     */
    public function getTocDepth()
    {
        return $this->tocDepth;
    }
}
