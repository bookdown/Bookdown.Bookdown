<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;

class Config
{
    protected $file;
    protected $dir;
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
        $this->initIndexOrigin();
    }

    protected function initFile($file)
    {
        $this->file = $file;
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
        if (! isset($this->json->title)) {
            throw new Exception("No title set in '{$this->file}'.");
        }
        $this->title = $this->json->title;
    }

    protected function initContent()
    {
        if (! isset($this->json->content)) {
            throw new Exception("No content listed in '{$this->file}'.");
        }

        $this->content = (array) $this->json->content;
        foreach ($this->content as $name => $origin) {
            $this->content[$name] = $this->fixPath($origin);
        }
    }

    protected function fixPath($path)
    {
        if (strpos($path, '://' !== false)) {
            return;
        }

        if ($path{0} === DIRECTORY_SEPARATOR) {
            return;
        }

        return $this->getDir() . ltrim($path, DIRECTORY_SEPARATOR);
    }

    protected function initIndexOrigin()
    {
        if (isset($this->content['index'])) {
            $this->indexOrigin = $this->content['index'];
            unset($this->content['index']);
        }
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

    public function getIndexOrigin()
    {
        return $this->indexOrigin;
    }
}
