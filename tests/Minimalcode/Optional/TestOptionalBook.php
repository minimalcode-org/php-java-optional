<?php

namespace Minimalcode\Optional;

/**
 * @method Book get()
 * @method Book|null orElse($other)
 * @method Book|null orElseGet(callable $supplier)
 * @method Book orElseThrow(callable $exceptionSupplier)
 */
class TestOptionalBook extends AbstractOptional
{
    /**
     * @inheritdoc
     */
    protected function supports($value)
    {
        return $value instanceof Book;
    }
}
