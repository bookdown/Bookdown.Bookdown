# Aura.Dispatcher

Provides tools to map arbitrary names to dispatchable objects, then to
dispatch to those objects using named parameters. This is useful for invoking
controller and command objects based on path-info parameters or command line
arguments, for dispatching to closure-based controllers, and for building
dispatchable objects from factories.

## Foreword

### Installation

This library requires PHP 5.4 or later, and has no userland dependencies.

It is installable and autoloadable via Composer as [aura/dispatcher](https://packagist.org/packages/aura/dispatcher).

Alternatively, [download a release](https://github.com/auraphp/Aura.Dispatcher/releases) or clone this repository, then require or include its _autoload.php_ file.

### Quality

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/auraphp/Aura.Dispatcher/badges/quality-score.png?b=develop-2)](https://scrutinizer-ci.com/g/auraphp/Aura.Dispatcher/)
[![Code Coverage](https://scrutinizer-ci.com/g/auraphp/Aura.Dispatcher/badges/coverage.png?b=develop-2)](https://scrutinizer-ci.com/g/auraphp/Aura.Dispatcher/)
[![Build Status](https://travis-ci.org/auraphp/Aura.Dispatcher.png?branch=develop-2)](https://travis-ci.org/auraphp/Aura.Dispatcher)

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

### Overview

First, an external routing mechanism such as [Aura.Router][] or a
micro-framework router creates an array of parameters. (Alternatively, the
parameters may be an object that implements [ArrayAccess][]).

[Aura.Router]: https://github.com/auraphp/Aura.Router
[ArrayAccess]: http://php.net/ArrayAccess

The parameters are then passed to the _Dispatcher_. It examines them and picks
an object to invoke with those parameters, optionally with a method determined
by the parameters.

The _Dispatcher_ then examines the returned result from that first invocation.
If the result is itself a dispatchable object, the _Dispatcher_ will
recursively dispatch the result until something other than a dispatchable
object is returned.

When a non-dispatchable result is returned, the _Dispatcher_ stops recursion
and returns the non-dispatchable result.

### Closures and Invokable Objects

First, we tell the _Dispatcher_ to examine the `controller` parameter to find
the name of the object to dispatch to:

```php
<?php
use Aura\Dispatcher\Dispatcher;

$dispatcher = new Dispatcher;
$dispatcher->setObjectParam('controller');
?>
```

Next, we set a closure object into the _Dispatcher_ using `setObject()`:

```php
<?php
$dispatcher->setObject('blog', function ($id) {
    return "Read blog entry $id";
});
?>
```

We can now dispatch to that closure by using the name as the value for
the `controller` parameter:

```php
<?php
$params = [
    'controller' => 'blog',
    'id' => 88,
];

$result = $dispatcher($params); // or call __invoke() directly
echo $result; // "Read blog entry 88"
?>
```

The same goes for invokable objects. First, define a class with an
`__invoke()` method:

```php
<?php
class InvokableBlog
{
    public function __invoke($id)
    {
        return "Read blog entry $id";
    }
}
?>
```

Next, set an instance of the object into the _Dispatcher_:

```php
<?php
$dispatcher->setObject('blog', new InvokableBlog);
?>
```

Finally, dispatch to the invokable object (the parameters and logic are
the same as above):

```php
<?php
$params = [
    'controller' => 'blog',
    'id' => 88,
];

$result = $dispatcher($params); // or call __invoke() directly
echo $result; // "Read blog entry 88"
?>
```

### Object Method

We can tell the _Dispatcher_ to examine the params for a method to call on the
object. This method will take precedence over the `__invoke()` method on an
object, if such a method exists.

First, tell the _Dispatcher_ to examine the value of the `action` param to
find the name of the method it should invoke.

```php
<?php
$dispatcher->setMethodParam('action');
?>
```

Next, define the object we will dispatch to; note that the method is `read()`
instead of `__invoke()`.

```php
<?php
class Blog
{
    public function read($id)
    {
        return "Read blog entry $id";
    }
}
?>
```

Then, we set the object into the _Dispatcher_ ...

```php
<?php
$dispatcher->setObject('blog', new Blog);
?>
```

... and finally, we invoke the _Dispatcher_; we have added an `action`
parameter with the name of the method to invoke:

```php
<?php
$params = [
    'controller' => 'blog',
    'action' => 'read',
    'id' => 88,
];

$result = $dispatcher($params); // or call __invoke() directly
echo $result; // "Read blog entry 88"
?>
```

### Embedding Objects in Parameters

If you like, you can place dispatchable objects directly in the parameters.
(This is often how micro-framework routers work.) For example, let's put a
closure into the `controller` parameter; when we invoke the _Dispatcher_, it
will invoke that closure.

```php
<?php
$params = [
    'controller' => function ($id) {
        return "Read blog entry $id";
    },
    'id' => 88,
];

$result = $dispatcher($params); // or call __invoke() directly
echo $result; // "Read blog entry 88"
?>
```

The same is true for invokable objects ...

```php
<?php
$params = [
    'controller' => new InvokableBlog,
    'id' => 88,
];

$result = $dispatcher($params); // or call __invoke() directly
echo $result; // "Read blog entry 88"
?>
```

... and for object-methods:


```php
<?php
$params = [
    'controller' => new Blog,
    'action' => 'read',
    'id' => 88,
];

$result = $dispatcher($params); // or call __invoke() directly
echo $result; // "Read blog entry 88"
?>
```


### Recursion and Lazy Loading

The _Dispatcher_ is recursive. After dispatching to the first object, if that
object returns a dispatchable object, the _Dispatcher_ will re-dispatch to
that object. It will continue doing this until the returned result is not a
dispatchable object.

Let's turn the above example of an invokable object in the _Dispatcher_ into a
lazy-loaded instantiation. All we have to do is wrap the instantiation in
another dispatchable object (in this example, a closure). The benefit of this
is that we can fill the _Dispatcher_ with as many objects as we like, and they
won't get instantiated until the _Dispatcher_ calls on them.

```php
<?php
$dispatcher->setObject('blog', function () {
    return new Blog;
});
?>
```

Then we invoke the dispatcher with the same params as before.

```php
<?php
$params = [
    'controller' => 'blog',
    'action' => 'read',
    'id' => 88,
];

$result = $dispatcher($params); // or call __invoke() directly
echo $result; // "Read blog entry 88"
?>
```

What happens is this:

- The _Dispatcher_ finds the 'blog' dispatchable object, sees that it
  is a closure, and invokes it with the params.

- The _Dispatcher_ examines the result, sees the result is a dispatchable
  object, and invokes it with the params.

- The _Dispatcher_ examines *that* result, sees that it is *not* a callable
  object, and returns the result.


## Sending The Array Of Params Directly

Sometimes you will want to send the entire array of parameters directly to the
object method or closure, as opposed to matching parameter keys with function
argument names. To do so, name a key in the parameters array for the argument
name that will receive them, and then set the parameters array into itself
using that name. If may be easier to do this by reference, or by copy,
depending on your needs.

```php
<?php
// a dispatchable closure that takes an array of params directly,
// not the individual params by keys matching argument names
$dispatcher->setObject('blog', function ($params) {
    return "Read blog entry {$params['id']}"
});

// the initial params
$params = [
     'controller' => 'blog',
     'action' => 'read',
     'id' => 88,
];

// set a params reference into itself; this corresponds with the
// 'params' closure argument
$params['params'] =& $params;

// dispatch
$result = $dispatcher($params); // or call __invoke() directly
echo $result; // "Read blog entry 88"
?>
```

## Refactoring To Architecture Changes

The _Dispatcher_ is built with the idea that some developers may begin with a
micro-framework architecture, and evolve over time toward a full-stack
architecture.

At first, the developer uses closures embedded in the params:

```php
<?php
$dispatcher->setObjectParam('controller');

$params = [
    'controller' => function ($id) {
        return "Read blog entry $id";
    },
    'id' => 88,
];

$result = $dispatcher($params); // or call __invoke() directly
echo $result; // "Read blog entry 88"
?>
```

After adding several controllers, the developer is likely to want to keep the
routing configurations separate from the controller actions. At this point the
developer may start putting the controller actions in the _Dispatcher_:

```php
<?php
$dispatcher->setObject('blog', function ($id) {
    return "Read blog entry $id!";
});

$params = [
    'controller' => 'blog',
    'id' => 88,
];

$result = $dispatcher($params); // or call __invoke() directly
echo $result; // "Read blog entry 88"
?>
```

As the number and complexity of controllers continues to grow, the developer
may wish to put the controllers into their own classes, lazy-loading along the
way:

```php
<?php
class Blog
{
    public function __invoke($id)
    {
        return "Read blog entry $id";
    }
}

$dispatcher->setObject('blog', function () {
    return new Blog;
});

$params = [
    'controller' => 'blog',
    'id' => 88,
];

$result = $dispatcher($params); // or call __invoke() directly
echo $result; // "Read blog entry 88"
?>
```

Finally, the developer may collect several actions into a single controller,
keeping related functionality in the same class. At this point the developer
should call `setMethodParam()` to tell the _Dispatcher_ where to find the
method to invoke on the dispatchable object.

```php
<?php
class Blog
{
    public function browse()
    {
        // ...
    }

    public function read($id)
    {
        return "Read blog entry $id";
    }

    public function edit($id)
    {
        // ...
    }

    public function add()
    {
        // ...
    }

    public function delete($id)
    {
        // ...
    }
}

$dispatcher->setMethodParam('action');

$dispatcher->setObject('blog', function () {
    return new Blog;
});

$params = [
    'controller' => 'blog',
    'action' => 'read',
    'id' => 88,
];

$result = $dispatcher($params); // or call __invoke() directly
echo $result; // "Read blog entry 88"
?>
```

## Construction-Based Configuration

You can set all dispatchable objects, along with the object parameter name and
the method parameter name, at construction time. This makes it easier to
configure the _Dispatcher_ object in a single call.

```php
<?php
$object_param = 'controller';
$method_param = 'action';
$objects = [
    'blog' => function () {
        return new BlogController;
    },
    'wiki' => function () {
        return new WikiController;
    },
    'forum' => function () {
        return new ForumController;
    },
];

$dispatcher = new Dispatcher($objects, $object_param, $method_param);
?>
```

## Intercessory Dispatch Methods

Sometimes your classes will have an intercessory method that picks an action
to run, either on itself or on another object. This package provides an
_InvokeMethodTrait_ to invoke a method on an object using named parameters.
(The _InvokeMethodTrait_ honors protected and private scopes.)

```php
<?php
use Aura\Dispatcher\InvokeMethodTrait;

class Blog
{
    use InvokeMethodTrait;

    public function __invoke(array $params)
    {
        $action = isset($params['action']) ? $params['action'] : 'index';
        $method = 'action' . ucfirst($action);
        return $this->invokeMethod($this, $method, $params);
    }

    protected function actionRead($id = null)
    {
        return "Read blog entry $id";
    }
}
?>
```

You can then dispatch to the object as normal, and it will determine its own
logical flow.

```php
<?php
$dispatcher->setObject('blog', function () {
    return new Blog;
});

$params = [
     'controller' => 'blog',
     'action' => 'read',
     'id' => 88,
];

$result = $dispatcher($params); // or call __invoke() directly
echo $result; // "Read blog entry 88"
?>
```
