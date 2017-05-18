<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 *
 * Logs to stdout/stderr.
 *
 * @package bookdown/bookdown
 *
 */
class Stdlog extends AbstractLogger
{
    /**
     *
     * The stdout file handle.
     *
     * @var resource
     *
     */
    protected $stdout;

    /**
     *
     * The stderr file handle.
     *
     * @var resource
     *
     */
    protected $stderr;

    /**
     *
     * Write to stderr for these log levels.
     *
     * @var array
     *
     */
    protected $stderrLevels = [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
    ];

    /**
     *
     * Constructor.
     *
     * @param resource $stdout Write to stdout on this handle.
     *
     * @param resource $stderr Write to stderr on this handle.
     *
     */
    public function __construct($stdout, $stderr)
    {
        $this->stdout = $stdout;
        $this->stderr = $stderr;
    }

    /**
     *
     * Logs with an arbitrary level.
     *
     * @param mixed $level The log level.
     *
     * @param string $message The log message.
     *
     * @param array $context Data to interpolate into the message.
     *
     */
    public function log($level, $message, array $context = [])
    {
        $replace = [];
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }
        $message = strtr($message, $replace) . PHP_EOL;

        $handle = $this->stdout;
        if (in_array($level, $this->stderrLevels)) {
            $handle = $this->stderr;
        }

        fwrite($handle, $message);
    }
}
