# Aura.Uri

[![Build Status](https://travis-ci.org/auraphp/Aura.Uri.png?branch=develop)](https://travis-ci.org/auraphp/Aura.Uri)

The `Auri.Uri` package provides objects to help you create and manipulate URLs,
including query strings and path elements. It does so by splitting up the pieces
of the URL and allowing you modify them individually; you can then fetch
them as a single URL string. This helps when building complex links,
such as in a paged navigation system.

This package is compliant with [PSR-0][], [PSR-1][], and [PSR-2][]. If you
notice compliance oversights, please send a patch via pull request.

[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md

## Getting Started

### Instantiation

The easiest way to instantiate a URL object is to use the factory instance
script, like so:

```php
<?php
$url_factory = require '/path/to/Aura.Uri/scripts/instance.php';
$url = $url_factory->newCurrent();
```

Alternatively, you can add the `src/` directory to your autoloader and
instantiate a URL factory object:

```php
<?php
use Aura\Uri\Url\Factory as UrlFactory;
use Aura\Uri\PublicSuffixList;

$psl = new PublicSuffixList(require '/path/to/Aura.Uri/data/public-suffix-list.php');
$url_factory = new UrlFactory($_SERVER, $psl);
$url = $url_factory->newCurrent();
```

When using the factory, you can populate the URL properties from a URL
string:

```php
<?php
$string = 'http://anonymous:guest@example.com/path/to/index.php/foo/bar.xml?baz=dib#anchor');
$url = $url_factory->newInstance($string);

// now the $url properties are ...
//
// $url->scheme    => 'http'
// $url->user      => 'anonymous'
// $url->pass      => 'guest'
// $url->host      => Aura\Uri\Host, with these methods:
//                      ->get()                     => 'example.com'
//                      ->getSubdomain()            => null
//                      ->getRegisterableDomain()   => 'example.com'
//                      ->getPublicSuffix()         => 'com'
// $url->port      => null
// $url->path      => Aura\Uri\Path, with these ArrayObject elements:
//                      ['path', 'to', 'index.php', 'foo', 'bar']
//                    and this method:
//                      ->getFormat() => '.xml'
// $url->query     => Aura\Uri\Query, with these ArrayObject elements:
//                      ['baz' => 'dib']
// $url->fragment  => 'anchor'
```

Alternatively, you can use the factory to create a URL representing the
current web request URI:

```php
<?php
$url = $url_factory->newCurrent();
```


### Manipulation

After we have created the URL object, we can modify the component parts, then
fetch a new URL string from the modified object.

```php
<?php
// start with a full URL
$string = 'http://anonymous:guest@example.com/path/to/index.php/foo/bar.xml?baz=dib#anchor';
$url = $url_factory->newInstance($string);

// change to 'https://'
$url->setScheme('https');

// remove the username and password
$url->setUser(null);
$url->setPass(null);

// change the value of 'baz' from 'dib' to 'zab'
$url->query->baz = 'zab';

// add a new query element called 'zim' with a value of 'gir'
$url->query->zim = 'gir';

// reset the path to something else entirely.
// this will additionally set the format to '.php'.
$url->path->setFromString('/something/else/entirely.php');

// add another path element
$url->path[] = 'another';

// get the url as a string; this will be without the scheme, host, port,
// user, or pass.
$new_url = $url->get();

// the $new_url string is as follows; notice how the format
// is always applied to the last path-element:
// /something/else/entirely/another.php?baz=zab&zim=gir#anchor

// get the full url string, including scheme, host, port, user, and pass.
$full_url = $url->getFull();

// the $full_url string is as follows:
// https://example.com/something/else/entirely/another.php?baz=zab&zim=gir#anchor
```

## Public Suffix List Host Parsing

### Host Component Parts

In addition to URL creation and manipulation, `Aura.Uri` is capable of parsing a
host into its component parts, namely the host's subdomain, registerable domain,
and public suffix. A host's component parts are available via properties on the
Aura.Uri host object, as seen in the examples above.

### Public Suffix List

This parsing capability is possible as a result of the [Public Suffix List][], a community
resource and initiative of Mozilla.

### Updating the Public Suffix List

As the Public Suffix List is both an external resource and a living document, it's
important that you update your copy of the list from time to time.  You can do this
by executing the provided `update.php` script.

`php /path/to/Aura.Uri/scripts/update.php`

Executing `update.php` will retrieve the most current version of the Public Suffix
List, parse it to an array, and store it in the `/path/to/Aura.Uri/data` directory.

[Public Suffix List]: http://publicsuffix.org/
