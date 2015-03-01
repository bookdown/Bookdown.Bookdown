<?php
namespace Bookdown\Bookdown\Service;

use Aura\Cli\Stdio;

class Timer
{
    protected $start;

    public function __construct(Stdio $stdio)
    {
        $this->stdio = $stdio;
        $this->start = microtime(true);
    }

    public function report()
    {
        $seconds = microtime(true) - $this->start;
        $seconds = trim(sprintf("%10.2f", $seconds));
        $this->stdio->outln("Completed in {$seconds} seconds.");
    }
}
