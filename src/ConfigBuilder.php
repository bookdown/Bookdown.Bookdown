<?php
namespace Bookdown\Bookdown;

class ConfigBuilder
{
    public function newConfig($file)
    {
        $data = $this->read($file);
        return new Config($file, $data);
    }

    public function newRootConfig($file)
    {
        $data = $this->read($file);
        return new RootConfig($file, $data);
    }

    protected function read($file)
    {
        return file_get_contents($file);
    }
}
