<?php

namespace Minimalcode\Optional;

/**
 * @method float get()
 * @method float|null orElse($other)
 * @method float orElseGet(callable $supplier)
 * @method float orElseThrow(callable $exceptionSupplier)
 */
class OptionalFloat extends AbstractOptional
{
    /**
     * @inheritdoc
     */
    protected function supports($value)
    {
        return \is_float($value);
    }
}
