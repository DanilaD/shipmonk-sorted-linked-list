<?php

/**
 * Advanced usage examples for SortedLinkedList.
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

echo "=== Advanced SortedLinkedList Usage ===\n\n";

// Example 1: Performance Test
echo "1. Performance Test (1000 elements):\n";
echo "-------------------------------------\n";

$start = microtime(true);
$perfList = new SortedLinkedList('int');

// Insert 1000 random integers
for ($i = 0; $i < 1000; $i++) {
    $perfList->insert(random_int(1, 10000));
}

$insertTime = microtime(true) - $start;
echo "Inserted 1000 random integers in: " . round($insertTime * 1000, 2) . "ms\n";
echo "List size: " . $perfList->count() . "\n";
echo "First 10 elements: " . implode(', ', array_slice($perfList->toArray(), 0, 10)) . "\n\n";

// Example 2: Duplicate Handling
echo "2. Duplicate Handling:\n";
echo "----------------------\n";

$dupList = new SortedLinkedList('int');
$dupList->insert(1);
$dupList->insert(1);
$dupList->insert(1);
$dupList->insert(2);
$dupList->insert(2);

echo "Duplicates allowed: " . implode(', ', $dupList->toArray()) . "\n";
echo "Count: " . $dupList->count() . "\n\n";

// Example 3: Complex Comparator (Custom Sorting)
echo "3. Complex Comparator (Sort by absolute value):\n";
echo "------------------------------------------------\n";

$absList = new SortedLinkedList('int', function (int $a, int $b): int {
    return abs($a) <=> abs($b);
});

$absList->insert(-5);
$absList->insert(3);
$absList->insert(-1);
$absList->insert(4);
$absList->insert(-2);

echo "Sorted by absolute value: " . implode(', ', $absList->toArray()) . "\n\n";

// Example 4: String Comparator (Case-insensitive)
echo "4. Case-insensitive String Sorting:\n";
echo "-----------------------------------\n";

$caseList = new SortedLinkedList('string', function (string $a, string $b): int {
    return strcasecmp($a, $b);
});

$caseList->insert('Apple');
$caseList->insert('banana');
$caseList->insert('Cherry');
$caseList->insert('date');

echo "Case-insensitive sorted: " . implode(', ', $caseList->toArray()) . "\n\n";

// Example 5: Memory Usage
echo "5. Memory Usage:\n";
echo "----------------\n";

$memoryBefore = memory_get_usage();
$memoryList = new SortedLinkedList('int');

for ($i = 0; $i < 100; $i++) {
    $memoryList->insert($i);
}

$memoryAfter = memory_get_usage();
$memoryUsed = $memoryAfter - $memoryBefore;

echo "Memory used for 100 integers: " . round($memoryUsed / 1024, 2) . " KB\n";
echo "Average per element: " . round($memoryUsed / 100, 2) . " bytes\n\n";

// Example 6: Clear and Reuse
echo "6. Clear and Reuse:\n";
echo "-------------------\n";

$reuseList = new SortedLinkedList('int');
$reuseList->insert(1);
$reuseList->insert(2);
$reuseList->insert(3);

echo "Before clear: " . implode(', ', $reuseList->toArray()) . " (count: " . $reuseList->count() . ")\n";

$reuseList->clear();

echo "After clear: " . implode(', ', $reuseList->toArray()) . " (count: " . $reuseList->count() . ")\n";

$reuseList->insert(10);
$reuseList->insert(20);

echo "After reuse: " . implode(', ', $reuseList->toArray()) . " (count: " . $reuseList->count() . ")\n\n";

// Example 7: Iterator with Custom Logic
echo "7. Iterator with Custom Logic:\n";
echo "------------------------------\n";

$iterList = new SortedLinkedList('int');
$iterList->insert(1);
$iterList->insert(2);
$iterList->insert(3);
$iterList->insert(4);
$iterList->insert(5);

echo "Finding even numbers:\n";
foreach ($iterList as $value) {
    if ($value % 2 === 0) {
        echo "  Even: $value\n";
    }
}

echo "\n=== Advanced Examples Completed! ===\n";
