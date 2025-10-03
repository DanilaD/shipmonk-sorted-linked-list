<?php

/**
 * Proper doubly linked list implementation.
 *
 * @category   ShipMonk
 * @package    SortedLinkedList
 * @author     ShipMonk Team <dev@shipmonk.com>
 * @license    MIT
 * @link       https://github.com/shipmonk/sorted-linked-list
 */

declare(strict_types=1);

namespace ShipMonk\SortedLinkedList\Collections;

use ShipMonk\SortedLinkedList\Domain\Exceptions\TypeMismatch;
use ShipMonk\SortedLinkedList\Domain\Exceptions\EmptyList;
use ShipMonk\SortedLinkedList\Domain\Exceptions\InvalidValue;

/**
 * Proper doubly linked list with sorted insertion.
 *
 * @template T of int|string
 */
final class SortedLinkedList implements SortedList
{
    /**
     * @var Node<T>|null
     */
    private ?Node $head = null;

    /**
     * @var Node<T>|null
     */
    private ?Node $tail = null;

    private int $count = 0;

    /**
     * Constructor.
     *
     * @param string $type The expected type ('int' or 'string')
     * @param callable|null $comparator Optional comparator function
     *
     * @throws InvalidValue If type is not 'int' or 'string'
     */
    public function __construct(
        private readonly string $type,
        private readonly mixed $comparator = null
    ) {
        if (!in_array($this->type, ['int', 'string'], true)) {
            throw new InvalidValue("Type must be 'int' or 'string'");
        }

        // Validate comparator arity
        if ($this->comparator !== null) {
            if (!is_callable($this->comparator)) {
                throw new InvalidValue('Comparator must be a valid callable function');
            }

            try {
                $reflection = new \ReflectionFunction($this->comparator);
                if ($reflection->getNumberOfParameters() !== 2) {
                    throw new InvalidValue('Comparator must accept exactly two arguments');
                }
            } catch (\ReflectionException $e) {
                throw new InvalidValue('Comparator must be a valid callable function');
            }
        }
    }

    /**
     * Insert value maintaining sorted order.
     *
     * @param T $value The value to insert
     *
     * @throws TypeMismatch If type mismatch
     * @throws InvalidValue If value is invalid
     */
    public function insert(mixed $value): void
    {
        $this->validateType($value);
        $this->validateValue($value);

        $newNode = new Node($value);

        if ($this->head === null) {
            // First node
            $this->head = $newNode;
            $this->tail = $newNode;
            $this->count = 1;
            return;
        }

        // Fast path: insert at head
        if ($this->head !== null && $this->compare($value, $this->head->value) <= 0) {
            $newNode->next = $this->head;
            $this->head->prev = $newNode;
            $this->head = $newNode;
            $this->count++;
            return;
        }

        // Fast path: insert at tail
        if ($this->tail !== null && $this->compare($value, $this->tail->value) >= 0) {
            $newNode->prev = $this->tail;
            $this->tail->next = $newNode;
            $this->tail = $newNode;
            $this->count++;
            return;
        }

        // Regular insertion
        $this->insertSorted($newNode);
        $this->count++;
    }

    /**
     * Remove value from list.
     *
     * @param T $value The value to remove
     *
     * @return bool True if value was removed, false if not found
     */
    public function remove(mixed $value): bool
    {
        $node = $this->findNode($value);
        if ($node === null) {
            return false;
        }

        $this->removeNode($node);
        return true;
    }

    /**
     * Check if value exists in list.
     *
     * @param T $value The value to check
     *
     * @return bool True if exists
     */
    public function contains(mixed $value): bool
    {
        return $this->findNode($value) !== null;
    }

    /**
     * Get first value.
     *
     * @return T First value
     *
     * @throws EmptyList If list is empty
     */
    public function first(): mixed
    {
        if ($this->head === null) {
            throw new EmptyList('first');
        }
        return $this->head->value;
    }

    /**
     * Get last value.
     *
     * @return T Last value
     *
     * @throws EmptyList If list is empty
     */
    public function last(): mixed
    {
        if ($this->tail === null) {
            throw new EmptyList('last');
        }
        return $this->tail->value;
    }

    /**
     * Get count of elements.
     */
    public function count(): int
    {
        return $this->count;
    }

    /**
     * Check if empty.
     */
    public function isEmpty(): bool
    {
        return $this->count === 0;
    }

    /**
     * Get all values as array.
     *
     * @return array<T>
     */
    public function toArray(): array
    {
        $values = [];
        $current = $this->head;
        while ($current !== null) {
            $values[] = $current->value;
            $current = $current->next;
        }

        return $values;
    }

    /**
     * Clear the list.
     */
    public function clear(): void
    {
        $this->head = null;
        $this->tail = null;
        $this->count = 0;
    }

    /**
     * Get iterator for foreach support.
     *
     * @return \Traversable<int, T>
     */
    public function getIterator(): \Traversable
    {
        for ($n = $this->head; $n !== null; $n = $n->next) {
            yield $n->value;
        }
    }

    /**
     * Remove a node (O(1) operation).
     *
     * @param Node<T> $node The node to remove
     */
    private function removeNode(Node $node): void
    {
        if ($node->prev !== null) {
            $node->prev->next = $node->next;
        } else {
            $this->head = $node->next;
        }

        if ($node->next !== null) {
            $node->next->prev = $node->prev;
        } else {
            $this->tail = $node->prev;
        }

        $this->count--;
    }

    /**
     * Find node by value with early exit optimization.
     *
     * @param T $value The value to find
     *
     * @return Node<T>|null The found node or null
     */
    private function findNode(mixed $value): ?Node
    {
        $current = $this->head;
        while ($current !== null) {
            $cmp = $this->compare($current->value, $value);
            if ($cmp === 0) {
                return $current;
            }
            if ($cmp > 0) {
                return null; // Nothing further can match due to sortedness
            }
            $current = $current->next;
        }

        return null;
    }

    /**
     * Insert node in sorted position.
     *
     * @param Node<T> $newNode The node to insert
     */
    private function insertSorted(Node $newNode): void
    {
        $current = $this->head;
        while ($current !== null) {
            if ($this->compare($newNode->value, $current->value) < 0) {
                // Insert before current
                $newNode->next = $current;
                $newNode->prev = $current->prev;

                if ($current->prev !== null) {
                    $current->prev->next = $newNode;
                } else {
                    $this->head = $newNode;
                }

                $current->prev = $newNode;
                return;
            }
            $current = $current->next;
        }

        // Insert at end
        $this->tail->next = $newNode;
        $newNode->prev = $this->tail;
        $this->tail = $newNode;
    }

    /**
     * Compare two values.
     *
     * @param T $a First value
     * @param T $b Second value
     *
     * @return int Comparison result (-1, 0, 1)
     */
    private function compare(mixed $a, mixed $b): int
    {
        if ($this->comparator !== null) {
            return ($this->comparator)($a, $b);
        }

        // Default comparison
        if ($this->type === 'int') {
            return $a <=> $b;
        }

        return strcmp((string) $a, (string) $b);
    }

    /**
     * Validate value type.
     *
     * @param mixed $value The value to validate
     *
     * @throws TypeMismatch If type mismatch
     */
    private function validateType(mixed $value): void
    {
        $actualType = gettype($value);
        $expectedType = $this->type === 'int' ? 'integer' : $this->type;

        if ($actualType !== $expectedType) {
            throw new TypeMismatch($this->type, $actualType);
        }
    }

    /**
     * Validate value content.
     *
     * @param mixed $value The value to validate
     *
     * @throws InvalidValue If value is invalid
     */
    private function validateValue(mixed $value): void
    {
        if ($this->type === 'string' && is_string($value) && trim($value) === '') {
            throw new InvalidValue('String cannot be empty or whitespace only');
        }
    }
}
