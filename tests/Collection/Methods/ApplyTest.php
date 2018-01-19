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
final class ApplyTest extends TestCase
{
    /**
     * Test that a callable function can applied to each element of a
     * Collection and return the modified result.
     *
     * @dataProvider applyData
     * @final
     * @param array $data
     * @param callable $modifier
     * @param array $expected
     * @return void
     */
    final public function testApplyCallableToEachElement(array $data, callable $modifier, array $expected): void
    {
        $collection = new Collection($data);
        $collection->apply($modifier);

        $this->assertEquals($expected, $collection->toArray());
    }

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
        $collection->apply($modifier, $args);

        $this->assertEquals($expected, $collection->toArray());
    }

    /**
     * Data for testMapCallableToEachElement
     *
     * @return array
     */
    final public function applyData(): array
    {
        return [
            'Simple multiplication' => [
                [1, 2, 3, 4, 5],

                function(int $value) {
                    return $value * 2;
                },

                [2, 4, 6, 8, 10],
            ],

            'String manipulation' => [
                [1, 2, 3, 'foo', 'bar', 4],

                function($value) {
                    if (is_string($value)) {
                        $value = 'changed!';
                    }

                    return $value;
                },

                [1, 2, 3, 'changed!', 'changed!', 4],
            ]
        ];
    }

    /**
     * Data for testMapCallableWithVariables
     *
     * @return array
     */
    final public function applyWithVariablesData(): array
    {
        return [
            'Simple multiplication' => [
                [1, 2, 3, 4, 5], // Start data

                // Modifying function
                function($value, array $args) {
                    return $value * $args['multiplier'];
                },

                [ 'multiplier' => 5 ], // Array of arguments

                [5, 10, 15, 20, 25], // expected
            ],

            'String manipulation' => [
                [1, 2, 3, 'foo', 'bar', 4], // Start data

                // Modyfing function
                function($value, array $args) {
                    if (is_string($value)) {
                        $value = $args[0];
                    }

                    return $value;
                },

                [ 'FOOBAR' ], // Array of arguments

                [1, 2, 3, 'FOOBAR', 'FOOBAR', 4], // expected
            ]
        ];
    }
}
