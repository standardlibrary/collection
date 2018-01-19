<?php
declare(strict_types=1);
/**
 * This file is part of the Standard Library Collection package.
 * For the full copyright information please view the LICENCE file that was
 * distributed with this package.
 *
 * @copyright Simon Deeley 2017
 */

use PHPUnit\Framework\TestCase;
use StandardLibrary\Collection;

/**
 * Test Collection
 *
 * @author Simon Deeley <simondeeley@users.noreply.github.com>
 * @uses StandardLibrary\Collection
 */
final class FirstTest extends TestCase
{
    /**
     * @var Collection
     */
    protected static $collection;

    /**
     * Setup collection
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::$collection = new Collection([
            1, 2, 3, 'foo', 'bar', 4, 'baz',
        ]);
    }

    /**
     * Test returns first element from array
     *
     * @return void
     */
    final public function testShouldReturnFirstElement(): void
    {
        $first = self::$collection->first();

        $this->assertEquals(1, $first);
    }

    /**
     * Test returns first element with user-defined filter
     *
     * @return void
     */
    final public function testShouldReturnFirstElementWithUserDefinedFilter(): void
    {
        $this->assertEquals(
            'foo',
            self::$collection->first(function($item) {
                return is_string($item);
            })
        );
    }

    /**
     * Test returns default value when no matches found
     *
     * @return void
     */
    final public function testShouldReturnDefaultValueWhenNoMatchesFound(): void
    {
        $this->assertEquals(
            'No Matches!',
            self::$collection->first(function($item) {
                return ($item === 'invalid') ? true : false;
            }, [], 'No Matches!')
        );
    }
}
