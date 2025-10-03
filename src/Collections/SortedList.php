<?php

/**
 * Domain interface for sorted list operations.
 *
 * @category   ShipMonk
 * @package    SortedLinkedList
 * @author     ShipMonk Team <dev@shipmonk.com>
 * @license    MIT
 * @link       https://github.com/shipmonk/sorted-linked-list
 */

declare(strict_types=1);

namespace ShipMonk\SortedLinkedList\Collections;

/**
 * Domain interface for sorted list operations.
 *
 * @template T of int|string
 */
interface SortedList extends \Countable, \IteratorAggregate
{
    /**
     * Insert value maintaining sorted order.
     *
     * @param T $value The value to insert
     *
     * @throws \Exception If insertion fails
     */
    public function insert(mixed $value): void;

    /**
     * Remove value from list.
     *
     * @param T $value The value to remove
     *
     * @return bool True if value was removed, false if not found
     */
    public function remove(mixed $value): bool;

    /**
     * Check if value exists in list.
     *
     * @param T $value The value to check
     *
     * @return bool True if exists
     */
    public function contains(mixed $value): bool;

    /**
     * Get first value.
     *
     * @return T|null First value or null if empty
     */
    public function first(): mixed;

    /**
     * Get last value.
     *
     * @return T|null Last value or null if empty
     */
    public function last(): mixed;

    /**
     * Get count of elements.
     */
    public function count(): int;

    /**
     * Get all values as array.
     *
     * @return array<T>
     */
    public function toArray(): array;

    /**
     * Get iterator for foreach support.
     *
     * @return \Traversable<int, T>
     */
    public function getIterator(): \Traversable;
}
