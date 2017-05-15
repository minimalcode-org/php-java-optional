<?php

namespace Minimalcode\Optional;

/**
 * @method Person get()
 * @method Person|null orElse($other)
 * @method Person|null orElseGet(callable $supplier)
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
