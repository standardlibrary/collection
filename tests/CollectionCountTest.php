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
final class CollectionCountTest extends TestCase
{
    /**
     * Test count method of a Collection
     *
     * @dataProvider countData
     * @param array $data
     * @param int $expected
     * @return void
     */
    final public function testCorrectlyCountsCollectionElements(array $data, int $expected): void
    {
        $collection = new Collection($data);

        // Assert two accessor methods report the same
        $this->assertSame($collection->count(), count($collection));

        // Assert size is as expected
        $this->assertEquals($expected, count($collection));
    }

    /**
     * Data for testCorrectlyCountsCollectionElements
     *
     * @return array
     */
    final public function countData(): array
    {
        return [
            'Empty Array' => [ [], 0 ],
            'Tiny array' => [ range(0,1), 2 ],
            'Normal array' => [ range('a', 'z'), 26 ],
            'Large array' => [ range(1, 1000000), 1000000],
        ];
    }
}
