<?php

namespace Minimalcode\Optional;

/**
 * @method string get()
 * @method string|null orElse($other)
 * @method string orElseGet(callable $supplier)
 * @method string orElseThrow(callable $exceptionSupplier)
 */
class OptionalString extends AbstractOptional
{
    /**
     * @inheritdoc
     */
    protected function supports($value)
    {
        return \is_string($value);
    }
}
