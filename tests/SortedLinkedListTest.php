<?php

declare(strict_types=1);

namespace ShipMonk\SortedLinkedList\Tests;

use PHPUnit\Framework\TestCase;
use ShipMonk\SortedLinkedList\Services\SortedLinkedListService;

/**
 * Simple test suite demonstrating SOLID architecture.
 */
class SortedLinkedListTest extends TestCase
{
    private SortedLinkedListService $service;

    protected function setUp(): void
    {
        $this->service = new SortedLinkedListService();
    }

    public function testInsertAndSort(): void
    {
        $this->service->insertValue(3);
        $this->service->insertValue(1);
        $this->service->insertValue(2);

        $stats = $this->service->getListStats();
        $this->assertEquals([1, 2, 3], $stats['values']);
        $this->assertEquals(3, $stats['count']);
    }

    public function testTypeSafety(): void
    {
        $this->service->insertValue(1);

        $result = $this->service->insertValue('string');
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Type mismatch', $result['message']);
    }

    public function testRemove(): void
    {
        $this->service->insertValue(1);
        $this->service->insertValue(2);

        $result = $this->service->removeValue(1);
        $this->assertTrue($result['success']);

        $stats = $this->service->getListStats();
        $this->assertEquals([2], $stats['values']);
    }

    public function testContains(): void
    {
        $this->service->insertValue(42);

        $result = $this->service->containsValue(42);
        $this->assertTrue($result['success']);

        $result = $this->service->containsValue(99);
        $this->assertFalse($result['success']);
    }

    public function testClear(): void
    {
        $this->service->insertValue(1);
        $this->service->insertValue(2);

        $result = $this->service->clearList();
        $this->assertTrue($result['success']);

        $stats = $this->service->getListStats();
        $this->assertTrue($stats['isEmpty']);
        $this->assertEquals(0, $stats['count']);
    }
}
