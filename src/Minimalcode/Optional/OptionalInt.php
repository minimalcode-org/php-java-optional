<?php

namespace Minimalcode\Optional;

/**
 * @method int get()
 * @method int|null orElse($other)
 * @method int orElseGet(callable $supplier)
 * @method int orElseThrow(callable $exceptionSupplier)
 */
class OptionalInt extends AbstractOptional
{
    /**
     * @inheritdoc
     */
    protected function supports($value)
    {
        return \is_int($value);
    }
}
