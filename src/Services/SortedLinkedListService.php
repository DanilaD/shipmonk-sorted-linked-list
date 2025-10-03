<?php

/**
 * Service class responsible for SortedLinkedList business logic.
 *
 * @category   ShipMonk
 * @package    SortedLinkedList
 * @author     ShipMonk Team <dev@shipmonk.com>
 * @license    MIT
 * @link       https://github.com/shipmonk/sorted-linked-list
 */

declare(strict_types=1);

namespace ShipMonk\SortedLinkedList\Services;

use ShipMonk\SortedLinkedList\Exceptions\SortedLinkedListException;
use ShipMonk\SortedLinkedList\Security\SecurityValidator;

/**
 * Service class responsible for SortedLinkedList business logic.
 *
 * Single Responsibility: Manages list operations and business rules.
 * Uses simple array with automatic sorting for simplicity.
 */
class SortedLinkedListService
{
    /**
     * Array of values in the list.
     *
     * @var array<string|int>
     */
    private array $values = [];

    /**
     * The type of values currently in the list.
     *
     * @var string|null
     */
    private ?string $valueType = null;

    /**
     * Constructor.
     *
     * @param SecurityValidator $securityValidator The security validator to use
     */
    public function __construct(
        private readonly SecurityValidator $securityValidator = new SecurityValidator()
    ) {
    }

    /**
     * Insert a value into the sorted list.
     *
     * @param string|int $value The value to insert
     *
     * @return array{success: bool, message: string}
     */
    public function insertValue(string|int $value): array
    {
        try {
            $validatedValue = $this->securityValidator->validateValue($value, count($this->values));
            
            // Type consistency check
            $valueType = gettype($validatedValue);
            if ($this->valueType !== null && $this->valueType !== $valueType) {
                throw SortedLinkedListException::typeMismatch($this->valueType, $valueType);
            }

            $this->insertSorted($validatedValue);
            $this->valueType = $this->valueType ?? $valueType;

            return ['success' => true, 'message' => "Value {$validatedValue} inserted successfully"];
        } catch (SortedLinkedListException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Insert value in sorted position using binary search.
     *
     * @param string|int $value The value to insert
     */
    private function insertSorted(string|int $value): void
    {
        if (empty($this->values)) {
            $this->values[] = $value;
            return;
        }

        $position = $this->findInsertPosition($value);
        array_splice($this->values, $position, 0, [$value]);
    }

    /**
     * Find insertion position using binary search.
     *
     * @param string|int $value The value to find position for
     *
     * @return int The insertion position
     */
    private function findInsertPosition(string|int $value): int
    {
        $left = 0;
        $right = count($this->values) - 1;

        while ($left <= $right) {
            $mid = ($left + $right) >> 1; // Bit shift is faster than division
            if ($this->values[$mid] < $value) {
                $left = $mid + 1;
            } else {
                $right = $mid - 1;
            }
        }

        return $left;
    }

    /**
     * Remove a value from the sorted list.
     *
     * @param string|int $value The value to remove
     *
     * @return array{success: bool, message: string}
     */
    public function removeValue(string|int $value): array
    {
        try {
            $validatedValue = $this->securityValidator->validateValue($value, count($this->values));

            $key = $this->findValuePosition($validatedValue);
            if ($key !== false) {
                $this->removeAtPosition($key);
                return ['success' => true, 'message' => "Value {$validatedValue} removed successfully"];
            }

            return ['success' => false, 'message' => "Value {$validatedValue} not found"];
        } catch (SortedLinkedListException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Find position of value using binary search.
     *
     * @param string|int $value The value to find
     *
     * @return int|false The position or false if not found
     */
    private function findValuePosition(string|int $value): int|false
    {
        $left = 0;
        $right = count($this->values) - 1;

        while ($left <= $right) {
            $mid = ($left + $right) >> 1; // Bit shift is faster than division
            if ($this->values[$mid] === $value) {
                return $mid;
            } elseif ($this->values[$mid] < $value) {
                $left = $mid + 1;
            } else {
                $right = $mid - 1;
            }
        }

        return false;
    }

    /**
     * Remove value at specific position efficiently.
     *
     * @param int $position The position to remove
     */
    private function removeAtPosition(int $position): void
    {
        $lastIndex = count($this->values) - 1;

        if ($position === $lastIndex) {
            // Remove last element - O(1)
            array_pop($this->values);
        } else {
            // Swap with last element and remove - O(1)
            $this->values[$position] = $this->values[$lastIndex];
            array_pop($this->values);
        }
    }

    /**
     * Check if list contains a value.
     *
     * @param string|int $value The value to check
     *
     * @return array{success: bool, message: string}
     */
    public function containsValue(string|int $value): array
    {
        try {
            $validatedValue = $this->securityValidator->validateValue($value, count($this->values));

            $contains = $this->findValuePosition($validatedValue) !== false;
            $message = $contains ? "Value {$validatedValue} found" : "Value {$validatedValue} not found";
            return ['success' => $contains, 'message' => $message];
        } catch (SortedLinkedListException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get list statistics.
     *
     * @return array{count: int, isEmpty: bool, valueType: string|null,
     *               first: string|int|null, last: string|int|null, values: array<string|int>}
     */
    public function getListStats(): array
    {
        $count = count($this->values);
        return [
            'count' => $count,
            'isEmpty' => $count === 0,
            'valueType' => $this->valueType,
            'first' => $this->values[0] ?? null,
            'last' => $count > 0 ? $this->values[$count - 1] : null,
            'values' => $this->values
        ];
    }

    /**
     * Clear the list.
     *
     * @return array{success: bool, message: string}
     */
    public function clearList(): array
    {
        $this->values = [];
        $this->valueType = null;

        return ['success' => true, 'message' => "List cleared successfully"];
    }
}
