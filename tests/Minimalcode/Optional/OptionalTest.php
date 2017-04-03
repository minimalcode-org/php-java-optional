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

    public function testIsPresentNot()
    {
        self::assertFalse(OptionalBook::ofEmpty()->isPresent());
    }

    public function testIfPresent()
    {
        $success = false;
        OptionalBook::of(new Book())
            ->ifPresent(function () use (&$success) {
                $success = true;
            });

        static::assertTrue($success);
    }

    public function testIfPresentNot()
    {
        $success = false;
        OptionalBook::ofEmpty()
            ->ifPresent(function () use (&$success) {
                $success = true;
            });

        static::assertFalse($success);
    }

    public function testFilter()
    {
        $book = new Book();
        $book->setPages(5);

        $optBook = OptionalBook::of($book)
            ->filter(function(Book $b) {
                return $b->getPages() > 5;
            });

        self::assertFalse($optBook->isPresent());
    }
}
