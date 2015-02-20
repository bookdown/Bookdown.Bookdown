# Aura.Cli_Kernel

This is a kernel for the [Aura.Cli_Project](https://github.com/auraphp/Aura.Cli_Project) package.

## Foreword

### Requirements

This kernel requires PHP 5.4 or later. Unlike Aura library packages, this 
kernel package has userland dependencies, which themselves may have other
dependencies:

- [aura/project-kernel](https://packagist.org/packages/aura/project-kernel)
- [aura/cli](https://packagist.org/packages/aura/cli)
- [aura/dispatcher](https://packagist.org/packages/aura/dispatcher)
- [psr/log](https://packagist.org/packages/psr/log)

### Installation

This kernel is installable and autoloadable via Composer with the following
`require` element in your `composer.json` file:

    "require": {
        "aura/cli-kernel": "dev-develop-2"
    }
    
Alternatively, download or clone this repository, then require or include its
_autoload.php_ file.

### Tests

[![Build Status](https://travis-ci.org/auraphp/Aura.Cli_Kernel.png?branch=develop-2)](https://travis-ci.org/auraphp/Aura.Cli_Kernel)

This kernel has 100% code coverage with [PHPUnit](http://phpunit.de). To run 
the tests at the command line, go to the `tests/kernel/` directory and issue 
`./phpunit.sh`.

### PSR Compliance

This kernel attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

### Community

To ask questions, provide feedback, or otherwise communicate with the Aura community, please join our [Google Group](http://groups.google.com/group/auraphp), follow [@auraphp on Twitter](http://twitter.com/auraphp), or chat with us on #auraphp on Freenode.

### Services

This kernel defines the following service objects in the _Container_:

- `aura/cli-kernel:dispatcher`: an instance of _Aura\Dispatcher\Dispatcher_
- `aura/cli-kernel:context`: an instance of _Aura\Cli\Context_
- `aura/cli-kernel:stdio`: an instance of _Aura\Cli\Stdio_
- `aura/cli-kernel:help_service`: an instance of _Aura\Cli_Kernel\HelpService_

Note that service definitions set at the kernel level may be reset at the project level.
