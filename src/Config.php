<?php
namespace Bookdown\Bookdown;

class Config
{
    protected $file;
    protected $title;
    protected $content;
    protected $indexOrigin = '';

    public function __construct($file, $data)
    {
        $json = json_decode($data);
        if (! $json) {
            throw new Exception("Malformed JSON in '{$file}'.");
        }

        if (! isset($json->title)) {
            throw new Exception("No title set in '{$file}'.");
        }
        $this->title = $json->title;

        if (! isset($json->content)) {
            throw new Exception("No content listed in '{$file}'.");
        }
        $this->content = (array) $json->content;

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
        return dirname($this->file) . DIRECTORY_SEPARATOR;
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
