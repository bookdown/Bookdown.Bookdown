# Aura.Project_Kernel

This kernel package exists as a base for [Aura.Cli_Kernel](https://github.com/auraphp/Aura.Cli_Kernel), [Aura.Web_Kernel](https://github.com/auraphp/Aura.Web_Kernel), and other future kernel types.

## Foreword

### Requirements

This kernel requires PHP 5.3 or later. Unlike Aura library packages, this
kernel package has userland dependencies, which themselves may have other
dependencies:

- [aura/di](https://packagist.org/packages/aura/di)
- [psr/log](https://packagist.org/packages/psr/log)

### Installation

This kernel is installable and autoloadable via Composer with the following
`require` element in your `composer.json` file:

    "require": {
        "aura/project-kernel": "dev-develop-2"
    }

Alternatively, download or clone this repository, then require or include its
_autoload.php_ file.

### Tests

[![Build Status](https://travis-ci.org/auraphp/Aura.Project_Kernel.png?branch=develop-2)](https://travis-ci.org/auraphp/Aura.Project_Kernel)

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

This package defines the following services in the _Container_:

- `aura/project-kernel:logger`: an instance of _Psr\Log\NullLogger_

Note that service definitions set at the kernel level may be reset at the project level.

## Configuration

Although configuration is a project-level concern, each Aura kernel and project handles it in the same way. Thus, we provide config documentation here to reduce repetition.

> N.b.: The examples throughout this document are for _Aura\Web_Project_; replace that with _Aura\Cli_Project_ or _Aura\Framework_Project_ as needed.


### Setting The Config Mode

Set the configuration mode using `$_ENV['AURA_CONFIG_MODE']`, either via a server variable or the project-level `config/_env.php` file. Each Aura project comes with `dev` (local development), `test` (shared testing/staging), and `prod` (production) modes pre-defined.

### Config File Location

Project-level configuration files are located in the project-level `config/` directory. Each configuration file is a class that extends _Aura\Di\Config_, and represents a configuration mode. Each configuration class has two methods:

- `define()`, which allows you to define params, setters, and services in the project _Container_; and

- `modify()`, which allows you to pull objects out of the _Container_ for programmatic modification. (This happens after the _Container_ is locked, so you cannot add new services or change params and setters here.)

The two-stage configuration system loads all the configuration classes in order by library, kernel, and project, then runs all the `define()` methods, locks the container, and finally runs all the `modify()` methods.

### Mapping Config Modes To Classes

The config modes are mapped to their related config class files via the project-level `composer.json` file in the `extra:aura:config` block. The entry key is the config mode, and the entry value is the class to use for that mode.

```json
{
    "autoload": {
        "psr-0": {
            "": "src/"
        },
        "psr-4": {
            "Aura\\Web_Project\\_Config\\": "config/"
        }
    },
    "extra": {
        "aura": {
            "type": "project",
            "config": {
                "common": "Aura\\Web_Project\\_Config\\Common",
                "dev": "Aura\\Web_Project\\_Config\\Dev",
                "test": "Aura\\Web_Project\\_Config\\Test",
                "prod": "Aura\\Web_Project\\_Config\\Prod"
            }
        }
    }
}
```

Config classes are autoloaded via a PSR-4 entry for that project namespace.

The "common" config class is always loaded regardless of the actual config mode.  For example, if the config mode is `dev`, first the _Common_ class is loaded, and then the _Dev_ class.


### Changing Config Settings

First, open the config file for the related config mode. To change configuration params, setters, and services, edit the `define()` method. To programmatically change a service after all definitions are complete, edit the `modify()` method.

### Adding A Config Mode

If you want to add a new configuration mode, say `qa`, you need to do three things.

First, create a config class for it in `config/`:

```php
<?php
namespace Aura\Web_Project\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Qa extends Config
{
    public function define(Container $di)
    {
        // define params, setters, and services here
    }

    public function modify(Container $di)
    {
        // modify existing services here
    }
}
?>
```

Next, edit the project-level `composer.json` file to add the new config mode with its related class:

```json
{
    "extra": {
        "aura": {
            "type": "project",
            "config": {
                "common": "Aura\\Web_Project\\_Config\\Common",
                "dev": "Aura\\Web_Project\\_Config\\Dev",
                "test": "Aura\\Web_Project\\_Config\\Test",
                "prod": "Aura\\Web_Project\\_Config\\Prod",
                "qa": "Aura\\Web_Project\\_Config\\Qa"
            }
        }
    }
}
```

Finally, run `composer update` so that Composer makes the necessary changes to the autoloader system.

