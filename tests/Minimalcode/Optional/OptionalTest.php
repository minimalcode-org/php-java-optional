<?php

namespace Minimalcode\Optional;

class OptionalTest extends \PHPUnit_Framework_TestCase
{
    public function testOptionalOfEmpty()
    {
        $empty = OptionalBook::ofEmpty();
        $empty2 = OptionalBook::ofEmpty();
        static::assertSame($empty, $empty2);
    }

    public function testOf()
    {
        $book = new Book();
        $optBook = OptionalBook::of($book);
        static::assertSame($book, $optBook->get());
    }

    public function testOfNullable()
    {
        $optBook = OptionalBook::ofNullable(null);
        static::assertNotNull($optBook);
    }

    public function testOrElse()
    {
        $book = new Book();
        $book2 = new Book();
        $value = OptionalBook::ofNullable($book)->orElse($book2);
        static::assertSame($book, $value);
        static::assertNotSame($book, $book2);
    }

    public function testOrElseNull()
    {
        $book = new Book();
        $value = OptionalBook::ofNullable(null)->orElse($book);
        static::assertSame($book, $value);
        static::assertNotNull($book);
    }

    public function testIsPresent()
    {
        $optBook = OptionalBook::ofEmpty();
        static::assertFalse($optBook->isPresent());

        $optBook2 = OptionalBook::of(new Book());
        static::assertTrue($optBook2->isPresent());
    }
}
