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
final class ArrayAccess_OffsetGetTest extends TestCase
{
    /**
     * Test should get valid offset from a Collection
     *
     * @dataProvider validOffsets
     * @param array $data
     * @param mixed $offset
     * @param mixed $expected
     * @return void
     */
    final public function testOffsetGetShouldReturnValueFromCollection(array $data, $offset, $expected): void
    {
        $collection = new Collection($data);

        $this->assertEquals($expected, $collection->get($offset));
    }

    /**
     * Test should return null when an offset does not exist
     *
     * @dataProvider invalidOffsets
     * @param array $data
     * @param mixed $offset
     * @return void
     */
    final public function testOffsetGetShouldReturnNullForInvalidOffset(array $data, $offset): void
    {
        $collection = new Collection($data);

        $this->assertNull($collection->get($offset));
    }

    /**
     * Test should return default value when an offset does not exist and
     * default value is passed to the function
     *
     * @dataProvider invalidOffsetsWithDefaultValue
     * @param mixed $offset
     * @param mixed $default
     * @param mixed $expected
     */
    final public function testOffsetGetShouldReturnDefaultValueWhenInvalid($offset, $default, $expected): void
    {
        $collection = new Collection([
            'foo' => 'bar', 2 => -1,
        ]);

        $this->assertEquals($expected, $collection->get($offset, $default));
    }

    /**
     * Data provider
     *
     * @return array
     */
    final public function validOffsets(): array
    {
        return [
            [
                [1, 2, 3, 4, 5],
                2,
                3,
            ],

            [
                ['a', 'b', ['foo', -100]],
                2,
                ['foo', -100],
            ],

            [
                [ 1 => 'foo', 'bar' => 'baz', true ],
                'bar',
                'baz',
            ],

            [
                [ true, false, null, true ],
                0,
                true,
            ]
        ];
    }

    /**
     * Data provider
     *
     * @return array
     */
    final public function invalidOffsets(): array
    {
        return [
            [
                [1, 2, 3, 4, 5], -1
            ],

            [
                [1=>'a', 2=>'b'], 0
            ],

            [
                [-1=>'a'], 1
            ]
        ];
    }

    /**
     * Data provider
     *
     * @return array
     */
    final public function invalidOffsetsWithDefaultValue(): array
    {
        return [
            [
                [1, 'baz', 'baz'],
                ['bar', true, true],
                [-1, 100, 100],
            ]
        ];
    }
}
