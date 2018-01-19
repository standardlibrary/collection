<?php
declare(strict_types=1);
/**
 * This file is part of the Standard Library Collection package.
 * For the full copyright information please view the LICENCE file that was
 * distributed with this package.
 *
 * @copyright Simon Deeley 2017
 */

namespace StandardLibrary;

use ArrayAccess;
use ArrayIterator;
use CachingIterator;
use Countable;
use IteratorAggregate;
use Serializable;
use Traversable;
use StandardLibrary\Contracts\TypeEquality;
use StandardLibrary\Contracts\Type\CollectionType;

/**
 * Collection
 *
 * @author Simon Deeley <simondeeley@users.noreply.github.com>
 */
class Collection implements ArrayAccess, CollectionType, Countable, IteratorAggregate, Serializable
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var CachingIterator
     */
    protected $iterator;

    /**
     * Returns the type of the object
     *
     * @return string
     */
    public static function getType(): string
    {
        return 'collection';
    }

    /**
     * Create a new Collection from an array
     *
     * @param array $data - OPTIONAL array to import
     * @return static
     */
    final public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Return collection as a PHP native array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

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
    final public function apply(callable $function, array $args = []): self
    {
        iterator_apply(

            // Use $this object as the iterator
            $this,

            // Wrap callable in closure that alway returns true
            function(CollectionType $collection) use ($function, $args) {

                // Set current key to result of callable
                $collection->set(

                    // Current key
                    $collection->key(),

                    // User-defined callable
                    $function(

                        // Current value
                        $collection->current(),

                        // Optional user-defined arguments
                        $args
                    )
                );

                return true;
            },

            // Pass $this (again) to the function for reasons unbeknown to science
            // {@see https://www.reddit.com/r/lolphp/comments/5zkn29/what_the_hell_with_iterator_apply/}
            [$this]
        );

        // Return to allow method-chaining
        return $this;
    }

    /**
     * Filter the current collection by a user-defined function
     *
     * @param callable $function - the user-defined function to filter by
     * @param array $args - OPTIONAL array of arguments to pass to the callable
     * @return self
     */
    final public function filter(callable $function, array $args = []): self
    {
        // Start a new Collection to collect the filtered results
        $collection = new Collection();

        // Iterate over current Collection
        iterator_apply(

            // Use $this object as the iterator
            $this,

            // Wrap callable in closure that alway returns true
            function(IteratorAggregate $iterator) use ($function, $args, &$collection) {

                // Pass current element and optional arguments to callable
                if ($function($iterator->current(), $args) === true) {

                    $collection->set(
                        $iterator->key(),
                        $iterator->current()
                    );
                }

                return true;
            },

            // Pass $this (again) to the function for reasons unbeknown to science
            // {@see https://www.reddit.com/r/lolphp/comments/5zkn29/what_the_hell_with_iterator_apply/}
            [$this]
        );

        // Return filtered Collection
        return $collection;
    }

    /**
     * Return first element matching criteria
     *
     * @param callable|null $filter - user-defined function to filter elements
     * @param array $args - OPTIONAL array of arguments to pass to the callable
     * @param mixed|null $default - OPTIONAL default value to return
     * @return mixed
     */
    public function first(callable $filter = null, array $args = [], $default = null)
    {
        // Use $this if no filter provided
        $filtered = ($filter === null)
            ? $this
            : $this->filter($function, $args)
        ;

        // Return first value or default if filtered collection is empty
        return empty($filtered) ? $default : reset($filtered);
    }

    /**
     * Return last element matching criteria
     *
     * @param callable|null $filter - user-defined function to filter elements
     * @param array $args - OPTIONAL array of arguments to pass to the callable
     * @param mixed|null $default - OPTIONAL default value to return
     * @return mixed
     */
    public function last(callable $filter = null, array $args = [], $default = null)
    {
        // Use $this if no filter provided
        $filtered = ($filter === null)
            ? $this
            : $this->filter($function, $args)
        ;

        // Return last value or default if filtered collection is empty
        return empty($filtered) ? $default : end($filtered);
    }

    /**
     * Reverse order of the Collection
     *
     * @return self
     */
    final public function reverse(): self
    {
        $this->data = array_reverse($this->data);

        return $this;
    }

    /**
     * Sets a key/pair value
     *
     * @param mixed $offset - the key (offset) to insert at
     * @param mixed $value - the value (data) to insert
     * @return self - SHOULD return same object to allow method-chaining
     */
    public function set($offset, $value): self
    {
        $this->offsetSet($offset, $value);

        return $this;
    }

    /**
     * Get a value
     *
     * @param mixed $offset - the offset to return
     * @param mixed $default - OPTIONAL value to use as a default
     * @return mixed
     */
    public function get($offset, $default = null)
    {
        return $this->offsetExists($offset) ? $this->data[$offset] : $default;
    }

    /**
     * Check that an offset, or series of offsets exists
     *
     * @param mixed $offset - the offset(s) to check
     * @return bool
     */
    public function exists($offset): bool
    {
        $offsets = is_array($offset) ? $offset : func_get_args();

        foreach ($offsets as $key) {

            if (! $this->offsetExists($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Deletes a key/pair
     *
     * @param mixed $offset
     * @return self
     */
    public function delete($offset): self
    {
        unset($this->data[$offset]);

        return $this;
    }

    /**
     * Set offset
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    final public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
     }

     /**
      * Unset offset
      *
      * @param mixed $offset
      * @return void
      */
     final public function offsetUnset($offset): void
     {
         unset($this->data[$offset]);
     }

    /**
     * Check that a offset exists
     *
     * @param mixed $offset
     * @return bool
     */
    final public function offsetExists($offset)
    {
        // Supress warning for non-integer or string keys
        return @array_key_exists($offset, $this->data);
    }

    /**
     * Return value
     *
     * @param mixed $offset
     * @return mixed
     */
    final public function offsetGet($offset)
    {
        return ($this->offsetExists($offset)) ? $this->data[$offset] : null;
    }

    /**
     * Counts the items in the set
     *
     * @return int
     */
    final public function count(): int
    {
        // If size if known, return it
        if (isset($this->size)) {
            return $this->size;
        }

        // Calculate, cache and then return the size
        return $this->size = count($this->data);
    }

    /**
     * Get the next element (look-ahead)
     *
     * Returns the next item in the Collection or null if the end of the
     * set is reached.
     *
     * @return mixed
     */
    final public function peek()
    {
        return $this->getCachedIterator()->hasNext()

            // Get the inner iterator which is always one step ahead of the CachedIterator
            ? $this->getCachedIterator()->getInnerIterator()->current()

            // No value, so return null
            : null;
    }

    /**
     * Get the current value
     *
     * @return mixed
     */
    final public function current()
    {
        return $this->getCachedIterator()->current();
    }

    /**
     * Get the current key
     *
     * @return mixed
     */
    final public function key()
    {
        return $this->getCachedIterator()->key();
    }

    /**
     * Advance pointer forward
     *
     * @return void
     */
    final public function next(): void
    {
        $this->getCachedIterator()->next();
    }

    /**
     * Rewind pointer
     *
     * @return void
     */
    final public function rewind(): void
    {
        $this->getCachedIterator()->rewind();
    }

    /**
     * Checks if the set containts anymore elements
     *
     * @return bool
     */
    final public function valid(): bool
    {
        return $this->getCachedIterator()->valid();
    }

    /**
     * Return an iterator
     *
     * @return Traversable
     */
    final public function getIterator(): Traversable
    {
        // Loop through the data
        foreach ($this->getCachedIterator() as $key => $value) {

            // Send the current key/value pair
            yield $key => $value;
        }
    }

    /**
     * Serialize
     *
     * @return string
     */
    final public function serialize()
    {
        return serialize($this->data);
    }

    /**
     * unserialize
     *
     * @param string $data
     * @return void
     */
    final public function unserialize($data)
    {
        $this->data = unserialize($data);
    }

    /**
     * Get the cache
     *
     * @return CachingIterator
     */
    final private function getCachedIterator(): CachingIterator
    {
        // If cache is generated, return it
        if (isset($this->iterator)) {
            return $this->iterator;
        }

        // Create and return new CachedIterator from the data
        return $this->iterator = new CachingIterator(

            // The raw data
            new ArrayIterator($this->data),

            // Always use original data and force all items to be cached on read
            CachingIterator::TOSTRING_USE_CURRENT | CachingIterator::FULL_CACHE
        );
    }
}
