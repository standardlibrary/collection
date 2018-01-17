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

use CachingIterator;
use Countable;
use Iterator;
use IteratorAggregate;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Serializable;
use SplFixedArray;
use Traversable;
use StandardLibrary\ImmutableArrayObject;
use StandardLibrary\Contracts\ArrayFunctions;
use StandardLibrary\Contracts\Sortable;
use InvalidArgumentException;

/**
 * Collection
 *
 * @author Simon Deeley <simondeeley@users.noreply.github.com>
 */
class Collection extends ImmutableArrayObject implements
    ArrayFunctions,
    Countable,
    Iterator,
    IteratorAggregate,
    Serializable,
    Sortable
{
    /**
     * @var SplFixedArray
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
     * @param array - OPTIONAL array to import
     * @return static
     */
    final public function __construct(array $data = [])
    {
        $this->data = SplFixedArray::fromArray($data);

        // Save memory
        unset($data);
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
        return $this->getCachedIterator->hasNext()

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
     * Return the Collection as a PHP array
     *
     * @return array
     */
    final public function toArray(): array
    {
        return $this->data->toArray();
    }

    /**
     * Append a new item
     *
     * @param mixed $data
     * @return static
     */
    final public function append($item): Collection
    {
        // Get raw data
        $data = $this->toArray();

        // Add item to the end
        $data[] = $item;

        // Create and return new Collection
        return new static($data);
    }

    /**
     * Prepend an item to the front of the Collection
     *
     * @param mixed $item
     * @return static
     */
    final public function prepend($item): Collection
    {
        // Get raw data
        $data = $this->toArray();

        // Push new item to the front of the array
        array_unshift($data, $item);

        // Create and return new Collection
        return new static($data);
    }

    /**
     * Filter the collection
     *
     * Takes a callback and sends each key/value pair to it. If the return value
     * is true then the key/pair are pushed to a newly created Collection.
     *
     * @param callable $function
     * @return static
     */
    final public function filter(callable $function): Collection
    {
        // Start with an empty array
        $data = [];

        // Loop through the data
        foreach ($this as $key => $value) {

            // If the callback returns true, push the item to the new array
            if ($function($key, $value) === true) {
                $data[$key] = $value;
            }
        }

        // Create and return a new Collection from the filtered results
        return new static($data);
    }

    /**
     * Map each value of the set
     *
     * Takes each key/value pair and applies a callback function to them. The
     * returned value from the function is used to create a modified set which
     * is then returned.
     *
     * @param callable $function
     * @return static
     */
    final public function map(callable $function): Collection
    {
        // Start with an empty array
        $data = [];

        foreach ($this as $key => $value) {

            // Apply the user-defined callback to the current key/pair
            $data[$key] = $function($key, $pair);
        }

        // Create and return a new Collection from the mapped results
        return new static($data);
    }

    /**
     * Flips the key/value pairs
     *
     * @return static
     */
    final public function flip(): Collection
    {
        return new static(array_flip($this->toArray()));
    }

    /**
     * Flatten the collection
     *
     * Recursively merges multi-dimensional arrays into one flat array. Array keys
     * are not preserved.
     *
     * @return static
     */
    final public function flatten(): Collection
    {
        $flattened = new RecursiveIteratorIterator(new RecursiveArrayIterator($this));

        return new static(iterator_to_array($flattened, false));
    }

    /**
     * Return an interator
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
     * Sorts the current collection
     *
     * @return static
     * @throws InvalidArgumentException - thrown when an invalid sort order is used
     */
    final public function sort(int $order = SORT_ASC, int $flags = SORT_REGULAR): self
    {
        // Get raw data to work with
        $data = $this->toArray();

        if ($order & SORT_ASC) {

            // Sort ascending
            asort($data, $flags);

        } elseif ($order & SORT_DESC) {

            // Sort descending
            arsort($data, $flags);

        } else {

            // Invalid sort order
            throw new InvalidArgumentException(
                'The sort order provided must be SORT_ASC or SORT_DESC'
            );
        }

        // Swap internal data for newly sorted data
        $this->swap($data);

        // return the Collection for method-chaining
        return $this;
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
            $this->data,

            // Always use original data and force all items to be cached on read
            CachingIterator::TOSTRING_USE_CURRENT | CachingIterator::FULL_CACHE
        );
    }

    /**
     * Swap out the internal data
     *
     * @param array $data
     * @return void
     */
    final private function swap(array $data): void
    {
        $this->data = SplFixedArray::fromArray($data);

        // Clear cached iterator
        unset($this->iterator);

        // Memory clearing
        unset($data);
    }
}
