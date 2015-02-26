# Aura.Framework_Project

This package provides a minimal framework for combined web and command-line projects.

By "minimal" we mean *very* minimal. The package provides only a dependency
injection container, a configuration system, a web router and dispatcher, a CLI dispatcher, a pair of web request and response objects, a pair of CLI context and standard I/O objects, and a logging instance.

This minimal implementation should not be taken as "restrictive". The DI
container, with its two-stage configuration system, allows a wide range of
programmatic service definitions. The router and dispatchers are built with
iterative refactoring in mind, so you can start with micro-framework-like
closures, and work your way into more complex controller and command objects of
your own design.

## Foreword

### Requirements

This project requires PHP 5.4 or later. Unlike Aura library packages, this
project package has userland dependencies, which themselves may have other
dependencies:

- [aura/cli-kernel](https://packagist.org/packages/aura/cli-kernel)
- [aura/web-kernel](https://packagist.org/packages/aura/web-kernel)
- [monolog/monolog](https://packagist.org/packages/monolog/monolog)

### Installation

Install this project via Composer to a `{$PROJECT_PATH}` of your choosing:

    composer create-project aura/framework-project {$PROJECT_PATH}

This will create the project skeleton and install all of the necessary packages.

### Tests

[![Build Status](https://travis-ci.org/auraphp/Aura.Framework_Project.png)](https://travis-ci.org/auraphp/Aura.Framework_Project)

This project has 100% code coverage with [PHPUnit](http://phpunit.de). To run the tests at the command line, go to the `tests/project/` directory and issue `./phpunit.sh`.

Alternatively, after you have installed the project, try the "hello world" CLI and web apps.

For the CLI, go to the project directory and issue the following command:

    cd {$PROJECT_PATH}
    php cli/console.php hello

You should see the output `Hello World!`. Try passing a name after `hello` to
see `Hello name!`.

For the web, start the built-in PHP server with the `web/` directory as the document root:

    cd {$PROJECT_PATH}
    php -S localhost:8000 -t web/

When you browse to <http://localhost:8000> you should see "Hello World!" as the output. Terminate the built-in server process thereafter. (Be sure to use the built-in PHP server only for testing, never for production.)

### PSR Compliance

This projects attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

### Community

To ask questions, provide feedback, or otherwise communicate with the Aura community, please join our [Google Group](http://groups.google.com/group/auraphp), follow [@auraphp on Twitter](http://twitter.com/auraphp), or chat with us on #auraphp on Freenode.

### Services

This package uses services defined by:

- [Aura.Project_Kernel](https://github.com/auraphp/Aura.Project_Kernel#services)
- [Aura.Cli_Kernel](https://github.com/auraphp/Aura.Cli_Kernel#services)

- [Aura.Web_Kernel](https://github.com/auraphp/Aura.Web_Kernel#services)

This project resets the following services:

- `logger`: an instance of `Monolog\Logger`

## Getting Started

This framework project package is not much more than a combination of the CLI and web project packages. Please see them for their respective "getting started" instructions"

- [Getting Started (CLI)](https://github.com/auraphp/Aura.Cli_Project#getting-started)

- [Getting Started (web)](https://github.com/auraphp/Aura.Web_Project#getting-started)
