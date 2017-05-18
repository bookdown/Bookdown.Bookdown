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
 *
 *
 * @package bookdown/bookdown
 *
 */
class IndexConfig
{
    protected $file;
    protected $dir;
    protected $isRemote = false;
    protected $json;
    protected $title;
    protected $content;
    protected $indexOrigin = '';
    protected $tocDepth;

    public function __construct($file, $data)
    {
        $this->initFile($file);
        $this->initJson($data);
        $this->init();
    }

    protected function init()
    {
        $this->initDir();
        $this->initTitle();
        $this->initContent();
        $this->initTocDepth();
    }

    protected function initFile($file)
    {
        $this->file = $file;
        $this->isRemote = strpos($file, '://') !== false;
    }

    protected function initDir()
    {
        $this->dir = dirname($this->file) . DIRECTORY_SEPARATOR;
    }

    protected function initJson($data)
    {
        $this->json = json_decode($data);
        if (! $this->json) {
            throw new Exception("Malformed JSON in '{$this->file}'.");
        }
    }

    protected function initTitle()
    {
        if (empty($this->json->title)) {
            throw new Exception("No title set in '{$this->file}'.");
        }
        $this->title = $this->json->title;
    }

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

    protected function initTocDepth()
    {
        $this->tocDepth = empty($this->json->tocDepth)
            ? 0
            : (int) $this->json->tocDepth;
    }

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

    public function isRemote()
    {
        return $this->isRemote;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getDir()
    {
        return $this->dir;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getTocDepth()
    {
        return $this->tocDepth;
    }
}
