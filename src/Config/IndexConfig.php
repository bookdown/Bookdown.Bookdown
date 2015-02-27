<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;

class IndexConfig
{
    protected $file;
    protected $dir;
    protected $isRemote = false;
    protected $json;
    protected $title;
    protected $content;
    protected $indexOrigin = '';

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
        $this->content = empty($this->json->content)
            ? array()
            : (array) $this->json->content;

        if (! $this->content) {
            throw new Exception("No content listed in '{$this->file}'.");
        }

        if (isset($this->content['index'])) {
            throw new Exception("Disallowed 'index' content in {$this->file}.");
        }

        foreach ($this->content as $name => $origin) {
            $this->content[$name] = $this->fixPath($origin);
        }
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
}
