<?php
declare(strict_types=1);
/**
 * This file is part of the Standard Library Collection package.
 * For the full copyright information please view the LICENCE file that was
 * distributed with this package.
 *
 * @copyright Simon Deeley 2017
 */

namespace StandardLibrary\Helpers;

/**
 * Array functions
 *
 * Various native PHP array functions wrapped into a resuable helper trait
 */
trait ArrayFunctionsHelperMethods
{
    /**
     * Return the underlying data
     *
     * @return array
     */
    abstract protected function &getData(): array;

    /**
     * Sets the underlying data
     *
     * @param array $data
     * @return void
     */
    abstract protected function setData(array $data): void;

    /**
     * Flip the key/values in an array
     *
     * @return self
     */
    public function flip()
    {
        $this->setData(array_flip($this->getData()));

        return $this;
    }

    /**
     * Returns all the keys in an array
     *
     * @param mixed $search - OPTIONAL search parameter
     * @param bool $strict - OPTIONAL strict search comparison
     * @return array
     */
    public function keys($search = null, bool $strict = true): array
    {
        return array_keys($this->getData(), $search, $strict);
    }

    /**
     * Pads out an array to a specified length
     *
     * Note that PHP has an upper limit of 1048576 elements that can be added to
     * an array {@see http://php.net/manual/en/function.array-pad.php}
     *
     * @param int $length - The length add to the array
     * @param mixed $value - The default value to add to the array
     * @return self
     */
    public function pad(int $length, $value = null)
    {
        $this->setData(
            array_pad($this->getData(), $length, $value)
        );

        return $this;
    }

    /**
     * Remove the last element of an array
     *
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->getData());
    }

    /**
     * Calculate product of an array
     *
     * @return int|float
     */
    public function product()
    {
        return array_product($this->getData());
    }

    /**
     * Remove element from front of the array
     *
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->getData());
    }

    /**
     * Calculate sum of array elements
     *
     * @return int|float
     */
    public function sum()
    {
        return array_sum($this->getData());
    }

    /**
     * Add one or more elements to the front of the array
     *
     * @param mixed ...$values
     * @return self
     */
    public function unshift(...$values)
    {
        array_unshift(
            $this->getData(),
            extract($values,
                    EXTR_PREFIX_IF_EXISTS | EXTR_PREFIX_INVALID,
                    '_collection_unshift'
            )
        );

        return $this;
    }

    /**
     * Return just the values from the array
     *
     * @return array
     */
    public function values(): array
    {
        return array_values($this->getData());
    }
}
