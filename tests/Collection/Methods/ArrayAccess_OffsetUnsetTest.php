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
final class ArrayAccess_OffsetUnsetTest extends TestCase
{
    /**
     * Test correctly deletes an offset
     *
     * @dataProvider data
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    final public function testDeletesValueFromCollectionWithKey($offset, $value): void
    {
        $collection = new Collection([
            0 => 'foo',
            1 => 'bar',
            'baz' => true,
        ]);

        $collection->offsetUnset($offset);

        $this->assertArrayNotHasKey($offset, $collection->toArray());
        $this->assertNotContains($value, $collection);
    }

    /**
     * Data for testDeletesValueFromCollectionWithKey
     *
     * @return array
     */
    final public function data(): array
    {
        return [
            [0, 'foo'],
            [1, 'bar'],
            ['baz', true],
        ];
    }
}
