Php implementation of Java-9 Optional, with 'generics' support

Features
=======
- Full 1:1 implementation of Java-9 Optional API
- Generic support (conceptually, obviously), for objects and primitives
- `OptionalBool`, `OptionalFloat`, `OptionalInt`, `OptionalString` already available
- Only 1 single class, optimized for performance
- Production ready

Usage
=======

```php
echo OptionalString::of('hello')->orElse('world');// echo 'hello' 

echo OptonalInt::ofNullable(null)->orElse(42);// echo 42
```

Generics
=======

```php
/**
 * @method Book get()
 * @method Book|null orElse($other)
 * @method Book orElseGet(callable $supplier)
 * @method Book orElseThrow(callable $exceptionSupplier)
 */
class OptionalBook extends AbstractOptional
{
    /**
     * @inheritdoc
     */
    protected function supports($value)
    {
        return $value instanceof Book;
    }
}

$book = new Book()
$optBook = OptionalBook::of($book);

```

http://download.java.net/java/jdk9/docs/api/java/util/Optional.html

https://www.mkyong.com/java8/java-8-optional-in-depth/

http://blog.codefx.org/java/dev/java-9-optional/

http://iteratrlearning.com/java9/2016/09/05/java9-optional.html
