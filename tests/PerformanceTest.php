<?php

declare(strict_types=1);

namespace ShipMonk\SortedLinkedList\Tests;

use PHPUnit\Framework\TestCase;
use ShipMonk\SortedLinkedList\Services\SortedLinkedListService;

/**
 * Performance tests for SortedLinkedList.
 */
class PerformanceTest extends TestCase
{
    private SortedLinkedListService $service;

    protected function setUp(): void
    {
        $this->service = new SortedLinkedListService();
    }

    /**
     * Test insertion performance with large dataset.
     */
    public function testInsertionPerformance(): void
    {
        $startTime = microtime(true);

        // Insert 1000 random integers
        for ($i = 0; $i < 1000; $i++) {
            $value = rand(1, 10000);
            $this->service->insertValue($value);
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        echo "\nInsertion Performance Test:\n";
        echo "Inserted 1000 values in: " . round($executionTime, 4) . " seconds\n";
        echo "Average per insert: " . round($executionTime * 1000 / 1000, 4) . " ms\n";

        // Verify list is sorted
        $stats = $this->service->getListStats();
        $this->assertEquals(1000, $stats['count']);
        $this->assertTrue($this->isSorted($stats['values']));

        // Performance should be reasonable (less than 1 second for 1000 inserts)
        $this->assertLessThan(1.0, $executionTime, 'Insertion took too long');
    }

    /**
     * Test search performance with large dataset.
     */
    public function testSearchPerformance(): void
    {
        // Pre-populate with 1000 values
        for ($i = 0; $i < 1000; $i++) {
            $this->service->insertValue($i);
        }

        $startTime = microtime(true);

        // Search for 100 random values
        for ($i = 0; $i < 100; $i++) {
            $value = rand(0, 999);
            $this->service->containsValue($value);
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        echo "\nSearch Performance Test:\n";
        echo "Searched 100 values in: " . round($executionTime, 4) . " seconds\n";
        echo "Average per search: " . round($executionTime * 1000 / 100, 4) . " ms\n";

        // Performance should be reasonable (less than 0.1 seconds for 100 searches)
        $this->assertLessThan(0.1, $executionTime, 'Search took too long');
    }

    /**
     * Test removal performance with large dataset.
     */
    public function testRemovalPerformance(): void
    {
        // Pre-populate with 1000 values
        for ($i = 0; $i < 1000; $i++) {
            $this->service->insertValue($i);
        }

        $startTime = microtime(true);

        // Remove 100 random values
        for ($i = 0; $i < 100; $i++) {
            $value = rand(0, 999);
            $this->service->removeValue($value);
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        echo "\nRemoval Performance Test:\n";
        echo "Removed 100 values in: " . round($executionTime, 4) . " seconds\n";
        echo "Average per removal: " . round($executionTime * 1000 / 100, 4) . " ms\n";

        // Performance should be reasonable (less than 0.1 seconds for 100 removals)
        $this->assertLessThan(0.1, $executionTime, 'Removal took too long');
    }

    /**
     * Test memory usage with large dataset.
     */
    public function testMemoryUsage(): void
    {
        $initialMemory = memory_get_usage();

        // Insert 1000 values
        for ($i = 0; $i < 1000; $i++) {
            $this->service->insertValue($i);
        }

        $finalMemory = memory_get_usage();
        $memoryUsed = $finalMemory - $initialMemory;

        echo "\nMemory Usage Test:\n";
        echo "Memory used for 1000 values: " . round($memoryUsed / 1024, 2) . " KB\n";
        echo "Average per value: " . round($memoryUsed / 1000, 2) . " bytes\n";

        // Memory usage should be reasonable (less than 1MB for 1000 integers)
        $this->assertLessThan(1024 * 1024, $memoryUsed, 'Memory usage too high');
    }

    /**
     * Test mixed operations performance.
     */
    public function testMixedOperationsPerformance(): void
    {
        $startTime = microtime(true);

        // Mixed operations: insert, search, remove
        for ($i = 0; $i < 500; $i++) {
            $value = rand(1, 1000);

            // Insert
            $this->service->insertValue($value);

            // Search
            $this->service->containsValue($value);

            // Remove (if exists)
            if (rand(0, 1)) {
                $this->service->removeValue($value);
            }
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        echo "\nMixed Operations Performance Test:\n";
        echo "500 mixed operations in: " . round($executionTime, 4) . " seconds\n";
        echo "Average per operation: " . round($executionTime * 1000 / 500, 4) . " ms\n";

        // Performance should be reasonable (less than 0.5 seconds for 500 operations)
        $this->assertLessThan(0.5, $executionTime, 'Mixed operations took too long');
    }

    /**
     * Check if array is sorted.
     *
     * @param array $values The values to check
     *
     * @return bool True if sorted
     */
    private function isSorted(array $values): bool
    {
        for ($i = 1; $i < count($values); $i++) {
            if ($values[$i] < $values[$i - 1]) {
                return false;
            }
        }
        return true;
    }
}
