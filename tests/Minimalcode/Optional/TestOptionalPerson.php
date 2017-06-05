<?php

namespace Minimalcode\Optional;

/**
 * @method Person get()
 * @method Person|null orElse($other)
 * @method Person orElseGet(callable $supplier)
 * @method Person orElseThrow(callable $exceptionSupplier)
 */
class TestOptionalPerson extends AbstractOptional
{
    /**
     * @inheritdoc
     */
    protected function supports($value)
    {
        return $value instanceof Person;
    }
}
