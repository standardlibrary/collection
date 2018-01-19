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
final class ArrayAccess_OffsetSetTest extends TestCase
{
    /**
     * Test correctly sets values on a Collection
     *
     * @dataProvider setData
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    final public function testSetsValuesOnCollection($offset, $value): void
    {
        $collection = new Collection();
        $collection->set($offset, $value);

        $this->assertArrayHasKey($offset, $collection->toArray());
        $this->assertContains($value, $collection);
    }

    /**
     * Data for testSetsValuesOnCollection
     *
     * @return array
     */
    final public function setData(): array
    {
       return [
           [1, 2],
           ['foo', 'bar'],
           [10, 'baz'],
           ['foo', true],
           [0, null],
       ];
    }
}
