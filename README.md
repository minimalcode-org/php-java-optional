Php implementation of Java-9 Optional, with 'generics' support

Usage
=======

```php
/**
 * @method Book get()
 * @method Book|null orElse($other)
 * @method Book|null orElseGet(callable $supplier)
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
