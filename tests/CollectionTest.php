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
final class CollectionTest extends TestCase
{
    /**
     * Test we can create a new Collection from an array
     *
     * @dataProvider arrays
     * @final
     * @param array $data
     * @return void
     */
    final public function testCreateCollectionFromArray(array $data): void
    {
        $collection = new Collection($data);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals($collection->toArray(), $data);
    }

    /**
     * Test Collection counts elements
     *
     * @dataProvider arrays
     * @final
     * @param array $data
     * @return void
     */
    final public function testCountElements(array $data): void
    {
        $collection = new Collection($data);

        $this->assertCount($collection->count(), $data);
    }

    /**
     * Test peeks ahead to next element
     *
     * @dataProvider arrays
     * @final
     * @param array $data
     * @return void
     */
    final public function testLookAheadInLoop(array $data): void
    {
        $collection = new Collection($data);
        $collection->rewind();

        while ($collection->valid()) {

            $this->assertEquals($collection->peek(), (null || $data[$collection->key()]));

            $collection->next();
        }
    }

    /**
     * Test appends element
     *
     * @dataProvider arrays
     * @final
     * @param array $data
     * @return void
     */
    final public function testAppendsElement(array $data): void
    {
        $a = new Collection($data);
        $b = $a->append('foo');

        $this->assertNotEquals($a, $b);
        $this->assertEquals('foo', end($b));
    }

    /**
     * Test prepends element
     *
     * @dataProvider arrays
     * @final
     * @param array $data
     * @return void
     */
    final public function testPrependsElement(array $data): void
    {
        $a = new Collection($data);
        $b = $a->prepend('foo');

        $this->assertNotEquals($a, $b);
        $this->assertEquals(reset($b), 'foo');
    }

    /**
     * Test filters array
     *
     * @final
     * @return void
     */
    final public function testFiltersCollection(): void
    {
        $a = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $b = $a->filter(function($item) {
            return $item % 2 === 1 ? true : false;
        });

        $this->assertNotEquals($a, $b);
        $this->assertCount(5, $b);
    }

    /**
     * Test maps array
     *
     * @final
     * @return void
     */
    final public function testMapsCollection(): void
    {
        $a = new Collection([1, 2, 3, 4, 5]);
        $b = $a->map(function($item) {
            return 'foo';
        });

        foreach ($b as $item) {
            $this->assertEquals('foo', $item);
        }
    }

    /**
     * Test flips arrays
     *
     * @dataProvider arrays
     * @final
     * @param array $data
     * @return void
     */
    final public function testFlipsKeyValuePairs(array $data): void
    {
        $a = new Collection($data);
        $b = $a->flip();

        $this->assertNotEquals($a, $b);
        $this->assertEquals(array_flip($data), $b->toArray());
    }

    /**
     * Arrays
     *
     * @return array
     */
    final public function arrays(): array
    {
        return [
            'Numeric' => [range(0, 100)],
            'String' => [range('a','z')],
        ];
    }
}
