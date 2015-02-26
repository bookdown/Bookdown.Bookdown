<?php
namespace Bookdown\Bookdown;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Content\RootPage;

class Processor
{
    protected $stdio;
    protected $processes;

    public function __construct(
        Stdio $stdio,
        array $processes
    ) {
        $this->stdio = $stdio;
        $this->processes = $processes;
    }

    public function __invoke(RootPage $root)
    {
        $this->stdio->outln("Processing pages.");
        foreach ($this->processes as $process) {
            $page = $root;
            while ($page) {
                $process($page);
                $page = $page->getNext();
            }
        }
    }
}
