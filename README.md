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

http://www.nurkiewicz.com/2013/08/optional-in-java-8-cheat-sheet.html
