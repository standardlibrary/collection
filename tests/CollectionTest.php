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
final class CollectionArrayAccessTest extends TestCase
{
    /**
     * Test correctly sets values on a Collection
     *
     * @dataProvider setData
     * @final
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
     * Test correctly gets a value from a Collection
     *
     * @dataProvider getData
     * @final
     * @param array $data
     * @param mixed $offset
     * @param mixed $expected
     * @return void
     */
    final public function testGetsValueFromCollection(array $data, $offset, $expected): void
    {
        $collection = new Collection($data);

        $this->assertEquals($expected, $collection->get($offset));
    }

    /**
     * Test correctly checks an offset exists
     *
     * @dataProvider hasData
     * @final
     * @param array $data
     * @param mixed $offset
     * @param mixed $expected
     * @return void
     */
     final public function testHasValueInCollection(array $data, $offset, $expected): void
     {
         $collection = new Collection($data);

         $this->assertEquals($expected, $collection->has($offset));
     }

     /**
      * Test correctly deletes an offset
      *
      * @dataProvider deleteData
      * @final
      * @param array $data
      * @param mixed $offset
      * @param mixed $value
      * @return void
      */
      final public function testDeletesValueFromCollection(array $data, $offset, $value): void
      {
          $collection = new Collection($data);
          $collection->delete($offset);

          $this->assertArrayNotHasKey($offset, $collection->toArray());
          $this->assertNotContains($value, $collection);
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

       /**
        * Data for testGetsValueFromCollection
        *
        * @return array
        */
        final public function getData(): array
        {
            return [
                [
                    [1, 2, 3, 4, 5],
                    2,
                    3,
                ],

                [
                    ['a', 'b', 'c'],
                    0,
                    'a',
                ],

                [
                    [ 1 => 'foo', 'bar' => 'baz', true ],
                    1,
                    'foo',
                ]
            ];
        }

        /**
         * Data for testHasValueInCollection
         *
         * @return array
         */
        final public function hasData(): array
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

        /**
         * Data for testDeletesValueFromCollection
         *
         * @return array
         */
         final public function deleteData(): array
         {
             return [
                 [
                     [1, 2, 3, 4, 5],
                     2,
                     3,
                 ],

                 [
                     ['a', 'b', 'c'],
                     0,
                     'a',
                 ],

                 [
                     [ 1 => 'foo', 'bar' => 'baz', true ],
                     1,
                     'foo',
                 ]
             ];
         }


}
