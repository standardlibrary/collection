<?php
declare(strict_types=1);
/**
 * This file is part of the Standard Library Collection package.
 * For the full copyright information please view the LICENCE file that was
 * distributed with this package.
 *
 * @copyright Simon Deeley 2017
 */

namespace StandardLibrary\Contracts;

/**
 * Array functions
 *
 * Contract for describing how an object can add, sort and filter items. It
 * mostly serves as a wrapper for PHP's built-in array functions (array_*)
 * {@link http://php.net/manual/en/ref.array.php}
 *
 * The key words “MUST”, “MUST NOT”, “REQUIRED”, “SHALL”, “SHALL NOT”, “SHOULD”,
 * “SHOULD NOT”, “RECOMMENDED”, “MAY”, and “OPTIONAL” in the comments are to be
 * interpreted as described in RFC 2119 {@link http://tools.ietf.org/html/rfc2119}
 */
interface ArrayFunctions
{
    /**
     * Return native PHP array
     *
     * This method SHOULD return a PHP array version of the object. It SHOULD NOT
     * throw any exceptions or errors. If there is no data to return then it
     * MUST return an empty array.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Append an item to the end of the current set
     *
     * This method SHOULD add the item to the end of the current set and MUST
     * return a newly created instance of the set, leaving the current object
     * unmodified.
     *
     * @param mixed $item - the item to add
     * @return static  - a new instance of the object
     */
    public function append($item);

    /**
     * Prepend an item to the begining of the current set
     *
     * This method SHOULD add the item to the begining of the current set and
     * MUST return a newly created instance containing the item leaving the
     * original set unmodified.
     *
     * @param mixed $item - the item to add
     * @return static - a new instance of the object
     */
    public function prepend($item);

    /**
     * Filters the current array object by the given callback
     *
     * This function iterates over the current set and passes the $key => $value
     * pairs to a callback function. This callback SHOULD accept at least two
     * parameters and MUST return a boolean value. If the value returned is FALSE
     * then the current item in the iteration is rejected from the returned set.
     *
     * @param callable $function - the function to filter items with
     * @return static
     */
    public function filter(callable $function);

    /**
     * Maps each item in the set by applying the given callback
     *
     * This function iterates over the current set and passes the $key => $value
     * pairs to the callback function. The callback SHOULD return the perform
     * whatever steps are necessary to modify or perform work on the current item.
     * The callback MUST return a value which will be used to replace the current
     * item in the set. They keys are NOT modified in this method.
     *
     * @param callable $function - the function to map the items to
     * @return static
     */
    public function map(callable $function);

    /**
     * Flips the key/value pairs
     *
     * This function swaps all the keys with their values. Note that the values
     * of array need to be valid keys, i.e. they need to be either integer or
     * string. A warning will be emitted if a value has the wrong type, and the
     * key/value pair in question will not be included in the result.
     *
     * If a value has several occurrences, the latest key will be used as its
     * value, and all others will be lost.
     *
     * @return static
     */
    public function flip(): Collection;

    /**
     * Flattens the set
     *
     * Takes the current data set and flattens any arrays into one single-dimensional
     * array. This function MAY overwrite existing key/value pairs if the same
     * keys are featured in deeper arrays so use with caution OR call
     * {@link Collectable::stripKeys()} with the $recursive option to strip keys
     * from multidimensional arrays.
     *
     * @return void
     */
    public function flatten(): void;

    /**
     * Strip keys from arrays
     *
     * Removes all the keys from key/value pairs and essentially makes the arrays
     * zero-index based. Call the function with the OPTIONAL $recursive option to
     * recurse into multidimensional arrays within the data set.
     *
     * @param bool $recursive - OPTIONALLY strip keys from multidimensional arrays
     * @return void
     */
    public function stripKeys(bool $recursive = false): void;

    /**
     * Merge one or more sets into the current set
     *
     * This method takes one or more ArrayType objects or arrays and attempts to
     * merge them into one, returning the resulting set. If an error occurs during
     * merger then this method MAY thrown an exception.
     *
     * @param mixed ...$sets - One or more sets to merge
     * @return static - Single, merged set
     */
    public function merge(...$sets);
}
