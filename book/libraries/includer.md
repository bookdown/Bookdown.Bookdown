# Aura.Includer

Provides a facility to include multiple files from specified directories, in
order, with variables extracted into a limited include scope.

## Foreword

### Installation

This library requires PHP 5.3 or later, and has no userland dependencies.

It is installable and autoloadable via Composer as [aura/includer](https://packagist.org/packages/aura/includer).

Alternatively, [download a release](https://github.com/auraphp/Aura.Includer/releases) or clone this repository, then require or include its _autoload.php_ file.

### Quality

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/auraphp/Aura.Includer/badges/quality-score.png?b=develop-2)](https://scrutinizer-ci.com/g/auraphp/Aura.Includer/)
[![Code Coverage](https://scrutinizer-ci.com/g/auraphp/Aura.Includer/badges/coverage.png?b=develop-2)](https://scrutinizer-ci.com/g/auraphp/Aura.Includer/)
[![Build Status](https://travis-ci.org/auraphp/Aura.Includer.png?branch=develop-2)](https://travis-ci.org/auraphp/Aura.Includer)

To run the unit tests at the command line, issue `phpunit -c tests/unit/`. (This requires [PHPUnit][] to be available as `phpunit`.)

[PHPUnit]: http://phpunit.de/manual/

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

### Community

To ask questions, provide feedback, or otherwise communicate with the Aura community, please join our [Google Group](http://groups.google.com/group/auraphp), follow [@auraphp on Twitter](http://twitter.com/auraphp), or chat with us on #auraphp on Freenode.


## Getting Started

### The Example Scenario

Let's say you have a series of packages, modules, plugins, etc. To do its
setup work, your framework or foundation needs to include certain files from
each of thse modules, such as configuration or routing files.

For our examples, the module directory structure will look like this:

    modules/
        foo/
            autoload.php
            config/
                default.php
                testing.php
            routes.php
        bar/
            autoload.php
        baz/
            autoload.php
            config/
                default.php

An example _autoload.php_ file might look like this:

```php
<?php
$loader->addNamespace('Module\Foo', __DIR__);
?>
```

An example config file might look like this:

```php
<?php
$config->setValue('db_host', 'localhost');
?>
```

An example _routes.php_ file might look like this:

```php
<?php
$router->setPath('/blog/read/{id}', function ($id) {
    // logic for the blog "read" action
});
?>
```

Because of the shared variables being used in each file, we need them to be
available, but we also want each file to be kept separate from the global
scope.

When including the configuration files, we need *both* the default
*and* an additional "mode" for overrides to the defaults.

If a file is missing, we can skip it without ill effect.

### Accomplishing The Task

The _Includer_ makes this scenario, and others like it, relatively easy.
First, we instantiate the _Includer_:

```php
<?php
use Aura\Includer\Includer;

$includer = new Includer;
?>
```

Next, we set the various directories we need to look through for files to
include:

```php
<?php
$includer->setDirs(array(
    '/path/to/modules/foo',
    '/path/to/modules/bar',
    '/path/to/modules/baz',
));
?>
```

Then we set the files to look for in each of the directories (we will include
both the default config and an override testing config):

```php
<?php
$includer->setFiles(array(
    'autoload.php',
    'config/default.php',
    'config/testing.php',
    'routes.php',
));
?>
```

Because the files happen to need local variables, we create them first, then
make them available to the include files:

```php
<?php
$loader = new Loader(...);
$config = new Config(...);
$router = new Router(...);

$includer->setVars(array(
    'loader' => $loader,
    'config' => $config,
    'router' => $router,
));
?>
```

Finally, after setting up the directory, files, and variables, we call the
`load()` method:

```php
<?php
$includer->load();
?>
```

This will create a separate scope for each include file, extract the
variables into that limited scope, and then include the file within that limited
scope. This means that no include file can affect the global state of the
application, except through the injected variables.

### Include Order

By default, the _Includer_ will include files in "directory order", represented
by the constant `Includer::DIR_ORDER`. This means that the _Includer_ visits the
first directory and attemps to load all the files noted in that directory,
then proceeds to the next directory. Given our above example, the loading
for `Includer::DIR_ORDER` would be:

    # first dir
    modules/foo/autoload.php
    modules/foo/config/default.php
    modules/foo/config/testing.php
    modules/foo/routes.php
    # second dir
    modules/bar/autoload.php
    # third dir
    modules/baz/autoload.php
    modules/baz/config/default.php

Alternatively, you can specify `load(Includer::FILE_ORDER)` to load files in
"file order". This means that the loader attemps to load the first file from
each directory, then the second file from each directory, and so on. Given our
above example, the loading for `Includer::FILE_ORDER` would be:

    # first file
    modules/foo/autoload.php
    modules/bar/autoload.php
    modules/baz/autoload.php
    # second file
    modules/foo/config/default.php
    modules/baz/config/default.php
    # third file
    modules/foo/config/testing.php
    # fourth file
    modules/foo/routes.php

### Strict Processing

By default, the _Includer_ is relatively strict about what path combinations
it will actually include. It will convert the directory + file path using
[realpath()](http://php.net/realpath) to get the absolute path, and then check
to see if that absolute path is in the same directory as specified in the
_Includer_. (This is because it's possible to use `../` and symbolic links to
point to file locations outside the specified directory.)  Files that are not
readable, or that are outside the specified directory, will not be included.

This type of processing is sometimes too strict; if you use symbolic links,
for example, the strict processing may exclude those files. To turn off strict
process, and only check if the file is readable, call `setStrict(false)`.

```php
<?php
// turn off strict processing
$includer->setStrict(false);
?>
```

### Globbing

Under the hood, the _Includer_ uses [glob()](http://php.net/glob) to find
files. This means you can use wildcards in the filenames to include files.

```php
<?php
// load all '.php' files in each of the directories
$includer->addFiles(array(
    'config/*.php',
    'routes/*.php'
);
?>
```

### Cache File

If you have dozens or scores of files that need to be included, that amount of
file system activity can be a performance drain. To mitigate this, it can be
useful to cache the files that would have been included.

The _Includer_ has a `read()` method to get the contents of the files to be
included and concatenate them, returning the concatenated contents for you to
cache in a file of your choosing. You can then point the _Includer_ to that
cached file; if it exists, the _Includer_ will use that file instead of
including the various different directory and file path combinations.

First, we get the text of the concatenated files using the `read()` method.
By default, it will concatenate the files in `Includer::DIR_ORDER`, but you
can specify `read(Includer::FILE_ORDER)` if you prefer.

```php
<?php
$text = $includer->read();
?>
```

The `read()` method will get the contents of each file, trim it, strip any
leading and trailing `<?php ?>` tags, replace the `__FILE__` constant with the
equivalent string file name, and replace the `__DIR__` constant with the
equivalent string directory name. (These replacements reflect the fact that
the code is being copied from its original location to a new location, and
the constants expect the value of thr original location.)

Now that we have the contents of the files, we add an opening `<?php` tag and
the time we created it, and then save it as a cache file:

```php
<?php
$text = '<?php /** '
      . date('Y-m-d H:i:s')
      . ' */' . PHP_EOL . PHP_EOL
      . $text;

file_put_contents('/path/to/cache_file.php', $text);
?>
```

Finally, we tell the _Includer_ where the cache file is. If it is readable,
the _Includer_ will use it on `load()`; otherwise, it will include the various
directory and file combinations.

```php
<?php
$includer->setCacheFile('/path/to/cache_file.php');
$includer->load(); // uses the cache file if it exists
?>
```

### Debugging

Sometimes it will be useful to see what files the _Includer_ actually found.
Use the `getDebug()` method to return an array of information about what
the _Includer_ found, in what order, and in what mode.
