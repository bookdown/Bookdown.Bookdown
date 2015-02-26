# Aura.SqlMapper_Bundle

> This is very much a work in progress. Use at your own risk.


## Foreword

### Installation

It is installable and autoloadable via Composer as [aura/sqlmapper-bundle](https://packagist.org/packages/aura/sqlmapper-bundle).

### Quality

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/auraphp/Aura.SqlMapper_Bundle/badges/quality-score.png?b=develop-2)](https://scrutinizer-ci.com/g/auraphp/Aura.SqlMapper_Bundle/)
[![Code Coverage](https://scrutinizer-ci.com/g/auraphp/Aura.SqlMapper_Bundle/badges/coverage.png?b=develop-2)](https://scrutinizer-ci.com/g/auraphp/Aura.SqlMapper_Bundle/)
[![Build Status](https://travis-ci.org/auraphp/Aura.SqlMapper_Bundle.png?branch=develop-2)](https://travis-ci.org/auraphp/Aura.SqlMapper_Bundle)

To run the unit tests, go to `tests/bundle/` and issue `./phpunit.sh`. (This requires [PHPUnit][] to be available as `phpunit`.)

[PHPUnit]: http://phpunit.de/manual/

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

### Community

To ask questions, provide feedback, or otherwise communicate with the Aura community, please join our [Google Group](http://groups.google.com/group/auraphp), follow [@auraphp on Twitter](http://twitter.com/auraphp), or chat with us on #auraphp on Freenode.

## Getting Started

### Entity

```php
<?php
namespace Vendor\Package;

class Post
{
    public $id;
    public $title;
    public $body;

    public function __construct($object = null)
    {
        foreach ((array) $object as $field => $value) {
            $this->$field = $value;
        }
    }
}
```

### Mapper

```php
<?php
namespace Vendor\Package;

use Aura\SqlMapper_Bundle\AbstractMapper;

class PostMapper extends AbstractMapper
{
    public function getTable()
    {
        return 'posts';
    }

    public function getPrimaryCol()
    {
        return 'id';
    }

    public function getColsFields()
    {
        return [
            'id'    => 'id',
            'title' => 'title',
            'body'  => 'body',
        ];
    }
}
```

## Usage

```php
<?php
use Aura\Sql\ConnectionLocator;
use Aura\Sql\ExtendedPdo;
use Aura\SqlMapper_Bundle\Query\ConnectedQueryFactory;
use Aura\SqlQuery\QueryFactory;

$connection_locator = new ConnectionLocator(function () use ($profiler) {
    $pdo = new ExtendedPdo('sqlite::memory:');
    $pdo->setProfiler($profiler);
    return $pdo;
});

$query = new ConnectedQueryFactory(new QueryFactory('sqlite'));

$mapper = new PostMapper($connection_locator, $query);
```

## Insert

```php
$object = new Vendor\Package\Post(array(
    'id' => null,
    'title' => 'Hello aura',
    'body' => 'Some awesome content',
));

$mapper->insert($object);
```

## Update 

```php
$object = new Vendor\Package\Post(array(
    'id' => 1,
    'title' => 'Hello aura',
    'body' => 'You are awesome!',
));

$mapper->update($object);
```

## Update only changes

```php
$object = $initial = new Vendor\Package\Post(array(
    'id' => 1,
    'title' => 'Hello aura',
    'body' => 'Some awesome content',
));

$object->title = 'Hello aura';
$object->body = 'You are awesome!';

$mapper->update($object, $initial);
```

## Fetch

```php
<?php
$actual = $mapper->select(['id', 'name'])
        ->where('id = ?', 1)
        ->fetchOne();
```

### fetchObject

```php
<?php
$data = $mapper->fetchObject(
    $mapper->select()->where('id = ?', 1)
);
```

### fetchObjectBy

```php
<?php
$data = $mapper->fetchObjectBy('id', 1);
```

### fetchCollection

```php
<?php
$data = $mapper->fetchCollection(
    $mapper->select()->where('id = ?', 1)
);
```

### fetchCollectionBy

```php
<?php
$data = $mapper->fetchCollectionBy('id', [1]);
```

## Delete

```php
<?php
$object = $mapper->fetchObjectBy('id', 1);
$mapper->delete($object);
```

## Object and Collection factory

By default the mapper returns standard class objects. You can change this behaviour when creating the mapper, by passing  callable to `object_factory` and `collection_factory`.


```php
<?php
$object_factory = function (array $row = array()) {
    return new Vendor\Package\Post($row);
};

$collection_factory = function (array $rows = array()) {
    $collection = array();
    foreach ($rows as $row) {
        $collection[] = new Vendor\Package\Post($row);
    }
    return $collection;
};

$mapper = new PostMapper($connection_locator, $query, $object_factory, $collection_factory);
```


## Override identity field

By default, mapper assumes a public property named for the primary column (or one that appears public via the magic __set() method). If the individual object uses a different property name, or uses a method instead, override `setIdentityValue` method to provide setter functionality.

Example : 

```php
<?php
namespace Vendor\Package;

use Aura\SqlMapper_Bundle\AbstractMapper;

class PostMapper extends AbstractMapper
{
    public function setIdentityValue($object, $value)
    {
        $object->setId($value);
    }
    // more code
}
```
