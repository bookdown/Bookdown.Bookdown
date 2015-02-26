# Aura.Web_Kernel

This is a kernel for the [Aura.Web_Project](https://github.com/auraphp/Aura.Web_Project) package.

## Foreword

### Requirements

This kernel requires PHP 5.4 or later. Unlike Aura library packages, this
kernel package has userland dependencies, which themselves may have other
dependencies:

- [aura/project-kernel](https://packagist.org/packages/aura/project-kernel)
- [aura/dispatcher](https://packagist.org/packages/aura/dispatcher)
- [aura/router](https://packagist.org/packages/aura/router)
- [aura/web](https://packagist.org/packages/aura/web)
- [psr/log](https://packagist.org/packages/psr/log)

### Installation

This kernel is installable and autoloadable via Composer with the following
`require` element in your `composer.json` file:

    "require": {
        "aura/web-kernel": "dev-develop-2"
    }

Alternatively, download or clone this repository, then require or include its
_autoload.php_ file.

### Tests

[![Build Status](https://travis-ci.org/auraphp/Aura.Web_Kernel.png?branch=develop-2)](https://travis-ci.org/auraphp/Aura.Web_Kernel)

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

- `aura/web-kernel:dispatcher`: an instance of _Aura\Dispatcher\Dispatcher_
- `aura/web-kernel:request`: an instance of _Aura\Web\Request_
- `aura/web-kernel:response`: an instance of _Aura\Web\Response_
- `aura/web-kernel:router`: an instance of _Aura\Router\Router_

Note that service definitions set at the kernel level may be reset at the project level.
