<?php

/**
 * Basic usage examples for SortedLinkedList.
 *
 * @category   ShipMonk
 * @package    SortedLinkedList
 * @author     ShipMonk Team <dev@shipmonk.com>
 * @license    MIT
 * @link       https://github.com/shipmonk/sorted-linked-list
 */

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use ShipMonk\SortedLinkedList\Collections\SortedLinkedList;
use ShipMonk\SortedLinkedList\Domain\Exceptions\TypeMismatch;
use ShipMonk\SortedLinkedList\Domain\Exceptions\EmptyList;
use ShipMonk\SortedLinkedList\Domain\Exceptions\InvalidValue;

echo "=== ShipMonk SortedLinkedList Usage Examples ===\n\n";

// Example 1: Integer List
echo "1. Integer List Example:\n";
echo "------------------------\n";

$intList = new SortedLinkedList('int');

// Insert integers (will be automatically sorted)
$intList->insert(5);
$intList->insert(1);
$intList->insert(3);
$intList->insert(2);
$intList->insert(4);

echo "Inserted: 5, 1, 3, 2, 4\n";
echo "Sorted result: " . implode(', ', $intList->toArray()) . "\n";
echo "Count: " . $intList->count() . "\n";
echo "First: " . $intList->first() . "\n";
echo "Last: " . $intList->last() . "\n\n";

// Example 2: String List
echo "2. String List Example:\n";
echo "------------------------\n";

$stringList = new SortedLinkedList('string');

$stringList->insert('zebra');
$stringList->insert('apple');
$stringList->insert('banana');
$stringList->insert('cherry');

echo "Inserted: 'zebra', 'apple', 'banana', 'cherry'\n";
echo "Sorted result: " . implode(', ', $stringList->toArray()) . "\n\n";

// Example 3: Custom Comparator (Descending Order)
echo "3. Custom Comparator Example (Descending):\n";
echo "-------------------------------------------\n";

$descList = new SortedLinkedList('int', fn(int $a, int $b) => $b <=> $a);

$descList->insert(1);
$descList->insert(5);
$descList->insert(3);
$descList->insert(2);

echo "Inserted: 1, 5, 3, 2 (with descending comparator)\n";
echo "Sorted result: " . implode(', ', $descList->toArray()) . "\n\n";

// Example 4: Remove Operations
echo "4. Remove Operations:\n";
echo "---------------------\n";

$removeList = new SortedLinkedList('int');
$removeList->insert(1);
$removeList->insert(2);
$removeList->insert(3);
$removeList->insert(4);
$removeList->insert(5);

echo "Before removal: " . implode(', ', $removeList->toArray()) . "\n";

// Remove middle element
$removed = $removeList->remove(3);
echo "Removed 3: " . ($removed ? 'Success' : 'Not found') . "\n";

// Remove non-existent element
$removed = $removeList->remove(99);
echo "Removed 99: " . ($removed ? 'Success' : 'Not found') . "\n";

echo "After removal: " . implode(', ', $removeList->toArray()) . "\n\n";

// Example 5: Contains Check
echo "5. Contains Operations:\n";
echo "-----------------------\n";

$containsList = new SortedLinkedList('int');
$containsList->insert(10);
$containsList->insert(20);
$containsList->insert(30);

echo "List: " . implode(', ', $containsList->toArray()) . "\n";
echo "Contains 20: " . ($containsList->contains(20) ? 'Yes' : 'No') . "\n";
echo "Contains 25: " . ($containsList->contains(25) ? 'Yes' : 'No') . "\n\n";

// Example 6: Iterator Support (foreach)
echo "6. Iterator Support:\n";
echo "--------------------\n";

$iterList = new SortedLinkedList('int');
$iterList->insert(7);
$iterList->insert(1);
$iterList->insert(4);
$iterList->insert(2);

echo "Using foreach:\n";
foreach ($iterList as $value) {
    echo "  Value: $value\n";
}
echo "\n";

// Example 7: Countable Support
echo "7. Countable Support:\n";
echo "---------------------\n";

$countList = new SortedLinkedList('int');
$countList->insert(1);
$countList->insert(2);
$countList->insert(3);

echo "Count using count(): " . count($countList) . "\n";
echo "Count using count(): " . $countList->count() . "\n\n";

// Example 8: Error Handling
echo "8. Error Handling Examples:\n";
echo "---------------------------\n";

try {
    // Type mismatch
    $intList->insert('string');
} catch (TypeMismatch $e) {
    echo "Type mismatch error: " . $e->getMessage() . "\n";
}

try {
    // Empty list operations
    $emptyList = new SortedLinkedList('int');
    $emptyList->first();
} catch (EmptyList $e) {
    echo "Empty list error: " . $e->getMessage() . "\n";
}

try {
    // Invalid type in constructor
    new SortedLinkedList('float');
} catch (InvalidValue $e) {
    echo "Invalid constructor error: " . $e->getMessage() . "\n";
}

echo "\n=== All Examples Completed Successfully! ===\n";
