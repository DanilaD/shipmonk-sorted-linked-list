<?php

/**
 * Exception thrown when attempting to access an empty list.
 *
 * @category   ShipMonk
 * @package    SortedLinkedList
 * @author     ShipMonk Team <dev@shipmonk.com>
 * @license    MIT
 * @link       https://github.com/shipmonk/sorted-linked-list
 */

declare(strict_types=1);

namespace ShipMonk\SortedLinkedList\Domain\Exceptions;

use Exception;

/**
 * Domain exception for empty list operations.
 */
class EmptyList extends Exception
{
    public function __construct(string $operation = 'operation')
    {
        parent::__construct("Cannot perform {$operation} on empty list");
    }
}
