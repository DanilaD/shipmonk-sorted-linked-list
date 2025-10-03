<?php

/**
 * Node for doubly linked list implementation.
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
 * Node for doubly linked list implementation.
 *
 * @template T of int|string
 * @internal
 */
final class Node
{
    /**
     * Constructor.
     *
     * @param T $value The node value
     * @param Node<T>|null $prev Previous node
     * @param Node<T>|null $next Next node
     */
    public function __construct(
        public mixed $value,
        public ?Node $prev = null,
        public ?Node $next = null
    ) {
    }
}
