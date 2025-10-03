<?php

/**
 * Exception thrown when attempting to insert an invalid value.
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
 * Domain exception for invalid values.
 */
class InvalidValue extends Exception
{
    public function __construct(string $reason)
    {
        parent::__construct("Invalid value: {$reason}");
    }
}
