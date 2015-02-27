<?php
namespace Bookdown\Bookdown\Process\Fake;

use Bookdown\Bookdown\Content\Page;

class FakeProcess
{
    public $info;

    public function __invoke(Page $page)
    {
        $this->info[] = $page->getTarget();
    }
}
