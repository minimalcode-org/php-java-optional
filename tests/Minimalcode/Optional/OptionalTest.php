<?php

namespace Minimalcode\Optional;

class OptionalTest extends \PHPUnit_Framework_TestCase
{
    public function testOptionalOfEmpty()
    {
        $empty = TestOptionalBook::ofEmpty();
        $empty2 = TestOptionalBook::ofEmpty();
        static::assertSame($empty, $empty2);
    }

    public function testOf()
    {
        $book = new Book();
        $optBook = TestOptionalBook::of($book);
        static::assertSame($book, $optBook->get());
    }

    public function testOfException()
    {
        $this->setExpectedException(
            \LogicException::class,
            'Value for Minimalcode\Optional\TestOptionalBook cannot be null, use Optional::ofNullable instead'
        );

        TestOptionalBook::of(null);
    }

    public function testGet()
    {
        $book = new Book();
        static::assertSame($book, TestOptionalBook::of($book)->get());
    }

    public function testMap()
    {
        $book = new Book();
        static::assertEquals(0, $book->getPages());

        $optBook = TestOptionalBook::of($book)
            ->map(function (Book $book) {
                $book->setPages(1000);
                return $book;
            });

        static::assertSame($book, $optBook->get());
        static::assertEquals(1000, $book->getPages());
    }

    public function testMapEmpty()
    {
        $executed = false;
        TestOptionalBook::ofEmpty()
            ->map(function ()  {
                throw new \LogicException();
            });

        static::assertFalse($executed);
    }

    public function testGetException()
    {
        $this->setExpectedException(
            \LogicException::class, 'No value present for Minimalcode\Optional\TestOptionalBook, use ::orElse instead'
        );

        TestOptionalBook::ofEmpty()->get();
    }

    public function testOrElseThrow()
    {
        $book = TestOptionalBook::of(new Book())
            ->orElseThrow(function () {
                return new \LogicException();
            });

        self::assertInstanceOf(Book::class, $book);
    }

    public function testOrElseThrowEmpty()
    {
        $this->setExpectedException(\LogicException::class, 'Error');

        TestOptionalBook::ofEmpty()
            ->orElseThrow(function () {
                return new \LogicException('Error');
            });
    }

    public function testOfNullable()
    {
        $optBook = TestOptionalBook::ofNullable(null);
        static::assertNotNull($optBook);
    }

    public function testOfEmptyTypeSafety()
	{
        $optBook = TestOptionalBook::ofEmpty();
	    $optPerson = TestOptionalPerson::ofEmpty();

        static::assertInstanceOf(TestOptionalBook::class, $optBook);
        static::assertInstanceOf(TestOptionalPerson::class, $optPerson);
        static::assertNotSame($optBook, $optPerson);
    }

    public function testIfAbsent()
    {
        $absent = false;
        TestOptionalBook::ofEmpty()
            ->ifAbsent(function () use (&$absent) {
                $absent = true;
            });

        static::assertTrue($absent);
    }

    public function testIfPresentOrElseWhenPresent()
    {
        $absent = false;
        TestOptionalBook::ofEmpty()
            ->ifPresentOrElse(
                function () {
                    throw new \InvalidArgumentException();
                },
                function () use (&$absent) {
                    $absent = true;
                }
            );

        static::assertTrue($absent);
    }

    public function testIfPresentOrElseWhenAbsent()
    {
        $present = false;
        TestOptionalBook::of(new Book())
            ->ifPresentOrElse(
                function () use (&$present) {
                    $present = true;
                },
                function (){
                    throw new \InvalidArgumentException();
                }
            );

        static::assertTrue($present);
    }

    public function testOrElse()
    {
        $book = new Book();
        $book2 = new Book();
        $value = TestOptionalBook::ofNullable($book)->orElse($book2);
        static::assertSame($book, $value);
        static::assertNotSame($book, $book2);
    }

    public function testOrElseNull()
    {
        $book = new Book();
        $value = TestOptionalBook::ofNullable(null)->orElse($book);
        static::assertSame($book, $value);
        static::assertNotNull($book);
    }

    public function testIsPresent()
    {
        $optBook = TestOptionalBook::ofEmpty();
        static::assertFalse($optBook->isPresent());

        $optBook2 = TestOptionalBook::of(new Book());
        static::assertTrue($optBook2->isPresent());
    }

    public function testIsPresentNot()
    {
        self::assertFalse(TestOptionalBook::ofEmpty()->isPresent());
    }

    public function testIfPresent()
    {
        $success = false;
        TestOptionalBook::of(new Book())
            ->ifPresent(function () use (&$success) {
                $success = true;
            });

        static::assertTrue($success);
    }

    public function testIfPresentNot()
    {
        $success = false;
        TestOptionalBook::ofEmpty()
            ->ifPresent(function () use (&$success) {
                $success = true;
            });

        static::assertFalse($success);
    }

    public function testFilter()
    {
        $book = new Book();
        $book->setPages(5);

        $optBook = TestOptionalBook::of($book)
            ->filter(function(Book $b) {
                return $b->getPages() > 5;
            });

        self::assertFalse($optBook->isPresent());
    }

    public function testFilterWithEmptyValue()
    {
        $optBook = TestOptionalBook::ofEmpty()
            ->filter(function(Book $b) {
                return $b instanceof Book;
            });

        self::assertFalse($optBook->isPresent());
    }

    public function testOrElseGet()
    {
        $book1 = new Book();
        $book1->setTitle('b1');

        $book1FromOpt = TestOptionalBook::of($book1)
            ->orElseGet(function () {
                $book2 = new Book();
                $book2->setTitle('b2');

                return $book2;
            });

        static::assertSame($book1FromOpt->getTitle(), $book1->getTitle());
    }

    public function testOrElseGetWithNull()
    {
        $this->setExpectedException(
            \InvalidArgumentException::class,
            'The value for Minimalcode\Optional\TestOptionalBook::orElseGet cannot be null'
        );

        TestOptionalBook::ofEmpty()
            ->orElseGet(function () {
                return null;
            });
    }

    public function testOrElseGetWithWrongType()
    {
        $this->setExpectedException(
            \InvalidArgumentException::class,
            'The value for Minimalcode\Optional\TestOptionalBook is unsupported'
        );

        TestOptionalBook::ofEmpty()
            ->orElseGet(function () {
                return 'string';
            });
    }

    public function testOrElseGetWithEmptyValue()
    {
        $book2 = TestOptionalBook::ofEmpty()
            ->orElseGet(function () {
                return new Book();
            });

        static::assertNotNull($book2);
    }

    public function testEquals()
    {
        $foo = 'foo';
        static::assertTrue(OptionalString::of($foo)->equals(OptionalString::of('foo')));
        static::assertTrue(OptionalString::of($foo)->equals(OptionalString::of($foo)));
        static::assertFalse(OptionalString::of($foo)->equals(OptionalString::of('bar')));
    }

    public function testTostring()
    {
        static::assertSame('Minimalcode\Optional\OptionalString[hello]', (string) OptionalString::of('hello'));
        static::assertSame('Minimalcode\Optional\OptionalInt[42]', (string) OptionalInt::of(42));
        static::assertSame('Minimalcode\Optional\OptionalBool[1]', (string) OptionalBool::of(true));
    }

    public function testOptionalPrimitive()
    {
        static::assertInstanceOf(OptionalString::class, OptionalString::of('foo'));
        static::assertInstanceOf(OptionalBool::class, OptionalBool::of(true));
        static::assertInstanceOf(OptionalFloat::class, OptionalFloat::of(2.5));
        static::assertInstanceOf(OptionalInt::class, OptionalInt::of(2));
    }

    public function testWrongOptionalString()
    {
        $this->setExpectedException(
            \InvalidArgumentException::class, 'The value for Minimalcode\Optional\OptionalString is unsupported'
        );

        OptionalString::of(-1);
    }

    public function testWrongOptionalInt()
    {
        $this->setExpectedException(
            \InvalidArgumentException::class, 'The value for Minimalcode\Optional\OptionalInt is unsupported'
        );

        OptionalInt::of('exception');
    }

    public function testWrongOptionalFloat()
    {
        $this->setExpectedException(
            \InvalidArgumentException::class, 'The value for Minimalcode\Optional\OptionalFloat is unsupported'
        );
        OptionalFloat::of('exception');

    }

    public function testWrongOptionalBool()
    {
        $this->setExpectedException(
            \InvalidArgumentException::class, 'The value for Minimalcode\Optional\OptionalBool is unsupported'
        );

        OptionalBool::of('exception');
    }
}
