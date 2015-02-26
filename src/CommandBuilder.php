<?php
namespace Bookdown\Bookdown;

use Aura\Cli\CliFactory;

class CommandBuilder
{
    public function newInstance($globals)
    {
        $cliFactory = new CliFactory();
        $context = $cliFactory->newContext($globals);
        $stdio = $cliFactory->newStdio();
        return new Command($context, $stdio);
    }
}
