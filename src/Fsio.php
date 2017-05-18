<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown;

/**
 *
 * Used for filesystem I/O operations.
 *
 * @package bookdown/bookdown
 *
 */
class Fsio
{
    /**
     *
     * Returns the contents of a file.
     *
     * @param string $file The file name.
     *
     * @return mixed
     *
     * @throws Exception on error.
     *
     */
    public function get($file)
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

    /**
     *
     * Writes (or overwrites) a file.
     *
     * @param string $file The file name.
     *
     * @param string $data The data for the file.
     *
     * @return mixed
     *
     * @throws Exception on error.
     *
     */
    public function put($file, $data)
    {
        $level = error_reporting(0);
        $result = file_put_contents($file, $data);
        error_reporting($level);

        if ($result !== false) {
            return $result;
        }

        $error = error_get_last();
        throw new Exception($error['message']);
    }

    /**
     *
     * Does a directory exist?
     *
     * @param string $dir The directory to look for.
     *
     * @return bool
     *
     */
    public function isDir($dir)
    {
        return is_dir($dir);
    }

    /**
     *
     * Creates a directory.
     *
     * @param string $dir The directory to create.
     *
     * @param int $mode Create with this permission mode.
     *
     * @param bool $deep Create intervening directories?
     *
     * @return void
     *
     * @throws Exception on error.
     *
     */
    public function mkdir($dir, $mode = 0777, $deep = true)
    {
        $level = error_reporting(0);
        $result = mkdir($dir, $mode, $deep);
        error_reporting($level);

        if ($result !== false) {
            return;
        }

        $error = error_get_last();
        throw new Exception($error['message']);
    }
}
