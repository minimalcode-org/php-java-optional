<?php

namespace Minimalcode\Optional;

/**
 * @method bool get()
 * @method bool|null orElse($other)
 * @method bool orElseGet(callable $supplier)
 * @method bool orElseThrow(callable $exceptionSupplier)
 */
class OptionalBool extends AbstractOptional
{
    /**
     * @inheritdoc
     */
    protected function supports($value)
    {
        return \is_bool($value);
    }
}
