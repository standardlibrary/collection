<?php
declare(strict_types=1);
/**
 * This file is part of the Standard Library Collection package.
 * For the full copyright information please view the LICENCE file that was
 * distributed with this package.
 *
 * @copyright Simon Deeley 2017
 */

namespace StandardLibrary\Contracts;

/**
 * Contract describing that an object can be sorted
 *
 * The key words “MUST”, “MUST NOT”, “REQUIRED”, “SHALL”, “SHALL NOT”, “SHOULD”,
 * “SHOULD NOT”, “RECOMMENDED”, “MAY”, and “OPTIONAL” in the comments are to be
 * interpreted as described in RFC 2119 {@link http://tools.ietf.org/html/rfc2119}
 *
 * @author
 */
interface Sortable
{
    /**
     * Sort an object
     *
     * Takes an OPTIONAL bitwise flags that defines the sort order and sort type.
     * Some flags can be combined to cutomise how the sorting is performed. This
     * function sorts objects in-situ, modifying the original object. It SHOULD
     * NOT produce a return value.
     *
     * @param int $order - OPTIONAL bitwise sort order flag
     * @param int $flag - OPTIONAL bitwise sort type flag
     * @return static
     */
    public function sort(int $order = SORT_ASC, int $flags = SORT_REGULAR);
}
