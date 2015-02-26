<?php
namespace Bookdown\Bookdown\Config;

use Bookdown\Bookdown\Exception;

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
        $level = error_reporting(0);
        $result = file_get_contents($file);
        error_reporting($level);

        if ($result !== false) {
            return $result;
        }

        $error = error_get_last();
        throw new Exception($error['message']);
    }
}
