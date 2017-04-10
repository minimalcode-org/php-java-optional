<?php

namespace Minimalcode\Optional;

/**
 * Php implementation of Java-9 Optional, with 'generics' support
 *
 * A container object which may or may not contain a non-null value.
 *
 * If a value is present, isPresent() returns true and get() returns the value.
 *
 * Additional methods that depend on the presence or absence of a contained value are provided,
 * such as orElse() (returns a default value if no value is present) and ifPresent()
 * (performs an action if a value is present).
 *
 * @author Fabio Piro
 * @see http://download.java.net/java/jdk9/docs/api/java/util/Optional.html
 */
abstract class AbstractOptional
{
    /**
     * Common instance for empty optional, for GC optimization
     */
    private static $EMPTY;

    /**
     * The value, or null is no value is present
     *
     * @var mixed|null
     */
    private $value;

    /**
     * Private Constructor
     */
    private function __construct()
    {
    }

    /**
     * Returns an Optional describing the given non-null value.
     *
     * @param mixed $value the value to be present, which must be non-null
     * @return static an Optional with the value present
     * @throws \InvalidArgumentException if value is null or unsupported
     */
    public static function of($value)
    {
        if (null === $value) {
            throw new \InvalidArgumentException('Value for Optional cannot be null, use Optional::ofNullable instead');
        }

        $self = new static();
        $self->value = $self->validate($value);

        return $self;
    }

    /**
     * Returns an empty Optional instance. No value is present for this Optional.
     * Replaces Java Optional::empty() ('empty' is a reserved word in php)
     *
     * @return static an empty Optional
     */
    public static function ofEmpty()
    {
        return self::$EMPTY ?: self::$EMPTY = new static();
    }

    /**
     * Returns an Optional describing the given value, if non-null, otherwise returns an empty Optional.
     *
     * @param mixed|null $value the possibly-null value to describe
     * @return static an Optional with a present value if the specified value
     * @throws \InvalidArgumentException
     */
    public static function ofNullable($value)
    {
        return null !== $value ? self::of($value) : self::ofEmpty();
    }

    /**
     * If a value is present, returns the value, otherwise throws LogicException.
     *
     * @return mixed the non-null value held by this Optional
     * @throws \LogicException if there is no value present
     */
    public function get()
    {
        if (null === $this->value) {
            throw new \LogicException('No value present, use ::orElse instead');
        }

        return $this->value;
    }

    /**
     * If a value is present, returns true, otherwise false.
     *
     * @return bool true if a value is present, otherwise false
     */
    public function isPresent()
    {
        return null !== $this->value;
    }

    /**
     * If a value is present, performs the given action with the value, otherwise does nothing.
     *
     * @param callable $action the action to be performed, if a value is present
     * @return static API change for allow chaining
     */
    public function ifPresent(callable $action)
    {
        if (null !== $this->value) {
            $action($this->value);
        }

        return $this;
    }

    /**
     * If a value is present, performs the given action with the value,
     * otherwise performs the given empty-based action.
     *
     * @param callable $action the action to be performed, if a value is present
     * @param callable $emptyAction the empty-based action to be performed, if no value is present
     * @return void
     */
    public function ifPresentOrElse(callable $action, callable $emptyAction)
    {
        if (null !== $this->value) {
            $action($this->value);
        } else {
            $emptyAction();
        }
    }

    /**
     * If a value is present, returns an Optional describing (as if by ofNullable(T))
     * the result of applying the given mapping function to the value, otherwise returns an empty Optional.
     *
     * If the mapping function returns a null result then this method returns an empty Optional
     *
     * @param callable $mapper the mapping function to apply to a value, if present
     * @return static an Optional describing the result of applying a mapping function to the value of this Optional,
     * if a value is present, otherwise an empty Optional
     * @throws \InvalidArgumentException
     */
    public function map(callable $mapper)
    {
        return null === $this->value ? self::ofEmpty() : self::ofNullable($mapper($this->value));
    }

    /**
     * If a value is present, returns the result of applying the given Optional-bearing mapping
     * function to the value, otherwise returns an empty Optional.
     *
     * This method is similar to map(Function), but the mapping function is one whose result
     * is already an Optional, and if invoked, flatMap does not wrap it within an additional
     * Optional.
     *
     * @param callable $mapper the mapping function to apply to a value, if present
     * @return mixed the result of applying an Optional-bearing mapping function to the value of this Optional,
     * if a value is present, otherwise an empty Optional
     * @throws \InvalidArgumentException if the mapping function is null or returns a null result
     */
    public function flatMap(callable $mapper)
    {
        if (null === $this->value) {
            return self::ofEmpty();
        }

        $result = $mapper($this->value);

        if (null === $result) {
            throw new \InvalidArgumentException('Mapper function cannot return null value');
        }

        return $result;
    }

    /**
     * If a value is present, and the value matches the given predicate,
     * returns an Optional describing the value, otherwise returns an empty Optional.
     *
     * @param callable $predicate the predicate to apply to a value, if present
     * @return static an Optional describing the value of this Optional, if a value
     * is present and the value matches the given predicate, otherwise an empty Optional
     * @throws \InvalidArgumentException if the predicate is null
     */
    public function filter(callable $predicate)
    {
        if (null === $this->value) {
            return $this;
        }

        return $predicate($this->value) ? $this : self::ofEmpty();
    }

    /**
     * If a value is present, returns the value, otherwise returns other.
     *
     * @param mixed|null $other the value to be returned, if no value is present. May be null.
     * @return mixed|null the value, if present, otherwise other
     * @throws \InvalidArgumentException
     */
    public function orElse($other)
    {
        return null !== $this->value ? $this->value : $this->validate($other);
    }

    /**
     * If a value is present, returns the value, otherwise returns the result produced
     * by the supplying function.
     *
     * @param callable $supplier the supplying function that produces a value to be returned
     * @return mixed|null the value, if present, otherwise the result produced by the supplying function
     * @throws \InvalidArgumentException if no value is present and the supplying function is null
     */
    public function orElseGet(callable $supplier)
    {
        return null !== $this->value ? $this->value: $this->validate($supplier());
    }

    /**
     * If a value is present, returns the value, otherwise throws an exception produced by
     * the exception supplying function.
     *
     * @param callable $exceptionSupplier the supplying function that produces an exception to be thrown
     * @return mixed the value, if present
     * @throws \Exception if there is no value present
     * @throws \InvalidArgumentException if no value is present and the exception supplying function is null
     */
    public function orElseThrow(callable $exceptionSupplier)
    {
        if (null === $this->value) {
            throw $exceptionSupplier();
        }

        return $this->value;
    }

    /**
     * If a value is present, returns an Optional describing the value, otherwise returns an Optional
     * produced by the supplying function.
     *
     * Replaces Java Optional::or() ('or' is a reserved word in php)
     *
     * @param callable $supplier the supplying function that produces an Optional to be returned
     * @return static returns an Optional describing the value of this Optional, if a value is present,
     * otherwise an Optional produced by the supplying function.
     * @throws \InvalidArgumentException is the value of the optional from the supplier is not supported
     */
    public function orElseOptional(callable $supplier)
    {
        if (null !== $this->value) {
            return $this;
        }

        $optional = $supplier();

        if (!$optional instanceof self) {
            throw new \InvalidArgumentException('Supplier must return a ' . __CLASS__ . ' instance');
        }

        return $optional;
    }

    /**
     * Indicates whether some other object is "equal to" this Optional.
     *
     * The other object is considered equal if:
     *   - it is also an Optional and;
     *   - both instances have no value present or;
     *   - the present values are "equal to" each other via equals()
     *
     * @param mixed $obj an object to be tested for equality
     * @return bool true if the other object is "equal to" this object otherwise false
     */
    public function equals($obj)
    {
        return $obj === $this || ($obj instanceof self && $obj->value === $this->value);
    }

    /**
     * Returns a non-empty string representation of this Optional suitable for debugging.
     * The exact presentation format is unspecified and may vary between implementations
     * and versions.
     *
     * @return string the string representation of this instance
     */
    public function __toString()
    {
        return \sprintf(__CLASS__ . '[%s]', (null !== $this->value ? $this->value : 'empty'));
    }

    /**
     * Mime the 'generics' support for this optional
     *
     * @param mixed $value cannot be null
     * @return boolean
     */
    protected abstract function supports($value);

    /**
     * Validates the value only if not null
     *
     * @param mixed|null $value
     * @return mixed|null the value, if valid or null
     * @throws \InvalidArgumentException if the value is not null and not supported
     */
    private function validate($value)
    {
        if (null !== $value && !$this->supports($value)) {
            throw new \InvalidArgumentException('The value for optional is unsupported');
        }

        return $value;
    }
}
