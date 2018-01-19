<?php
declare(strict_types=1);
/**
 * This file is part of the Standard Library Collection package.
 * For the full copyright information please view the LICENCE file that was
 * distributed with this package.
 *
 * @copyright Simon Deeley 2017
 */

namespace StandardLibrary\Contracts\Type;

use StandardLibrary\Contracts\Type\Type;

/**
 * Collection type interface
 *
 * Unlike other typical Type-interfaces, this interface is not intended to be
 * immutable rather it allows some modification.
 *
 * The key words “MUST”, “MUST NOT”, “REQUIRED”, “SHALL”, “SHALL NOT”, “SHOULD”,
 * “SHOULD NOT”, “RECOMMENDED”, “MAY”, and “OPTIONAL” in the comments are to be
 * interpreted as described in RFC 2119
 * {@link http://tools.ietf.org/html/rfc2119}
 */
interface CollectionType extends Type
{
    /**
     * Return collection as a PHP native array
     *
     * This method MUST return a native PHP array of the data stored in the
     * collection.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Sets a key/pair value
     *
     * This MAY result in overwriting existing values depending on the key
     * (offset) passed. If overwriting an existing value is not desired then
     * consuming libraries or users SHOULD check for existence of the key first
     * or use {@link CollectionType::append($value)} instead.
     *
     * @param mixed $offset - the key (offset) to insert at
     * @param mixed $value - the value (data) to insert
     * @return self - SHOULD return same object to allow method-chaining
     */
    public function set($offset, $value);

    /**
     * Get a value
     *
     * Returns a value from the collection. The second parameter can be used to
     * determine a return-value should the offset not exist.
     *
     * @param mixed $offset - the offset to return
     * @param mixed $default - OPTIONAL value to use as a default
     * @return mixed
     */
    public function get($offset, $default = null);

    /**
     * Check that an offset exists
     *
     * Checks that a given offset exists. Method MUST return boolean TRUE if the
     * offset exists or FALSE otherwise.
     *
     * @param mixed $offset - the offset to check
     * @return bool
     */
    public function exists($offset): bool;

    /**
     * Deletes a key/pair
     *
     * It is RECOMMENED that this method checks the existence of the key before
     * trying to delete it. It MAY thrown an exception if the key does not
     * exists or it MAY ignore the error but it SHOULD always return the same
     * instance of itself to allow method chaining.
     *
     * @param mixed $offset
     * @return self
     */
    public function delete($offset);

    /**
     * Apply a callback to every element of the collection
     *
     * This method applies the suplied $function callable to each item of the
     * collection. The callable MAY modify the items but it MUST return a value.
     * The method SHOULD return the same instance to allow method-chaining.
     *
     * @param callable $function - the user-defined function to apply
     * @param array $args - OPTIONAL array of arguments to pass to the callable
     * @return self
     */
    public function apply(callable $function, array $args = []);

    /**
     * Filter the current collection by a user-defined function
     *
     * The callable function MUST accept at least one parameter and MAY accept
     * an array of OPTIONAL parameters that will be passed to the function. The
     * callable MUST return boolean TRUE or FALSE. A true value will result in
     * the current element of the array being added to a new Collection. This
     * new collection Collection MUST be returned, even if it's empty.
     *
     * @param callable $function - the user-defined function to filter by
     * @param array $args - OPTIONAL array of arguments to pass to the callable
     * @return static
     */
    public function filter(callable $function, array $args = []);

    /**
     * Return first element matching criteria
     *
     * Apply the given callable to each element. The firt element that matches
     * the criteria and returns a boolean TRUE value MUST be returned. If no
     * callable is supplied then the method SHOULD return the first element from
     * the array.
     *
     * @param callable|null $filter - user-defined function to filter elements
     * @param array $args - OPTIONAL array of arguments to pass to the callable
     * @param mixed|null $default - OPTIONAL default value to return
     * @return mixed
     */
    public function first(callable $filter = null, array $args = [], $default = null);

    /**
     * Return last element matching criteria
     *
     * Apply the given callable to each element. The last element that matches
     * the criteria and returns a boolean TRUE value MUST be returned. If no
     * callable is supplied then the method SHOULD return the last element from
     * the array.
     *
     * @param callable|null $filter - user-defined function to filter elements
     * @param array $args - OPTIONAL array of arguments to pass to the callable
     * @param mixed|null $default - OPTIONAL default value to return
     * @return mixed
     */
    public function last(callable $filter = null, array $args = [], $default = null);
}
