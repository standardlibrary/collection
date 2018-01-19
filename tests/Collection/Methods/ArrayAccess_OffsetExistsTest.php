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
final class ArrayAccess_OffsetExistsTest extends TestCase
{
    /**
     * Test correctly checks an offset exists
     *
     * @dataProvider existsData
     * @param array $data
     * @param mixed $offset
     * @param mixed $expected
     * @return void
     */
     final public function testKeyExistsInCollection(array $data, $offset, $expected): void
     {
         $collection = new Collection($data);

         $this->assertEquals($expected, $collection->exists($offset));
     }

    /**
     * Data for testKeyExistsInCollection
     *
     * @return array
     */
    final public function existsData(): array
    {
        return [
            [
                [1, 2, 3, 4, 5],
                2,
                true
            ],

            [
                ['a', 'b', 'c'],
                10,
                false,
            ],

            [
                [ 1 => 'foo', 'bar' => 'baz', true ],
                'bar',
                true
            ]
        ];
    }
}
