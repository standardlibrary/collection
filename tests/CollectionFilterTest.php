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
final class CollectionFilterTest extends TestCase
{
    /**
     * Test that user can pass variables into the applied function
     *
     * @dataProvider applyWithVariablesData
     * @final
     * @param array $data
     * @param callable $modifier
     * @param array $args
     * @param array $expected
     * @return void
     */
    final public function testApplyCallableWithVariables(array $data, callable $modifier, array $args, array $expected): void
    {
        $collection = new Collection($data);
        $collection->filter($modifier, $args);

        $this->assertEquals($expected, $collection->toArray());
    }

    /**
     * Data for testMapCallableWithVariables
     *
     * @return array
     */
     final public function applyWithVariablesData(): array
     {
         return [
             'Filter when divisible by arguments' => [
                 [1, 2, 3, 4, 5, 6, 7, 8], // Start data

                 // Modifying function
                 function(int $value, array $args) {
                     return ($value % $args['divisor'] === 1) ? true : false;
                 },

                 [ 'divisor' => 2 ], // Array of arguments

                 [2, 4, 6, 8], // expected
             ],
         ];
     }
 }
