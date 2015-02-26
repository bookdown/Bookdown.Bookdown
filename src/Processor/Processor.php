<?php
namespace Bookdown\Bookdown\Processor;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Content\RootPage;

class Processor
{
    protected $processors;

    public function __construct(array $processors = null)
    {
        $this->processors = $processors;
    }

    public function __invoke(RootPage $root, Stdio $stdio)
    {
        $stdio->outln("Applying processors.");
        foreach ($this->processors as $processor) {
            $page = $root;
            while ($page) {
                $processor($page, $stdio);
                $page = $page->getNext();
            }
        }
    }
}
