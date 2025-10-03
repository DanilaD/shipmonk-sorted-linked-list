<?php

/**
 * Exception thrown when attempting to insert a value of a different type.
 *
 * @category   ShipMonk
 * @package    SortedLinkedList
 * @author     ShipMonk Team <dev@shipmonk.com>
 * @license    MIT
 * @link       https://github.com/shipmonk/sorted-linked-list
 */

declare(strict_types=1);

namespace ShipMonk\SortedLinkedList\Domain\Exceptions;

use TypeError;

/**
 * Domain exception for type mismatch.
 */
class TypeMismatch extends TypeError
{
    public function __construct(string $expected, string $actual)
    {
        parent::__construct("Type mismatch: expected {$expected}, got {$actual}");
    }
}
