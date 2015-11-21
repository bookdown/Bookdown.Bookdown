<?php
namespace Bookdown\Bookdown;

class FakeFsio extends Fsio
{
    protected $files = array();
    protected $dirs = array();

    public function get($file)
    {
        return $this->files[$file];
    }

    public function put($file, $data)
    {
        $this->files[$file] = $data;
    }

    public function isDir($dir)
    {
        return isset($this->dirs[$dir]);
    }

    public function mkdir($dir, $mode = 0777, $deep = true)
    {
        $this->dirs[$dir] = true;
    }

    /**
     * @param array $dirs
     */
    public function setDirs(array $dirs)
    {
        $this->dirs = $dirs;
    }

    /**
     * @param array $files
     */
    public function setFiles(array $files)
    {
        $this->files = $files;
    }


}
