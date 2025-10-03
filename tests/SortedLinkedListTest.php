<?php

/**
 * Unit tests for SortedLinkedList.
 */

declare(strict_types=1);

namespace ShipMonk\SortedLinkedList\Tests;

use PHPUnit\Framework\TestCase;
use ShipMonk\SortedLinkedList\Collections\SortedLinkedList;
use ShipMonk\SortedLinkedList\Domain\Exceptions\TypeMismatch;
use ShipMonk\SortedLinkedList\Domain\Exceptions\InvalidValue;
use ShipMonk\SortedLinkedList\Domain\Exceptions\EmptyList;

/**
 * Unit tests for SortedLinkedList.
 */
class SortedLinkedListTest extends TestCase
{
    /**
     * Test insertion maintains sorted order.
     */
    public function testInsertionMaintainsSortedOrder(): void
    {
        $list = new SortedLinkedList('int');

        $list->insert(3);
        $list->insert(1);
        $list->insert(2);

        $this->assertEquals([1, 2, 3], $list->toArray());
    }

    /**
     * Test type homogeneity enforcement.
     */
    public function testTypeHomogeneityEnforcement(): void
    {
        $list = new SortedLinkedList('int');
        $list->insert(1);

        $this->expectException(TypeMismatch::class);
        $list->insert('string');
    }

    /**
     * Test remove operations.
     */
    public function testRemoveOperations(): void
    {
        $list = new SortedLinkedList('int');
        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        // Remove middle
        $this->assertTrue($list->remove(2));
        $this->assertEquals([1, 3], $list->toArray());

        // Remove head
        $this->assertTrue($list->remove(1));
        $this->assertEquals([3], $list->toArray());

        // Remove tail
        $this->assertTrue($list->remove(3));
        $this->assertTrue($list->isEmpty());

        // Remove non-existent
        $this->assertFalse($list->remove(99));
    }

    /**
     * Test contains operations.
     */
    public function testContainsOperations(): void
    {
        $list = new SortedLinkedList('int');
        $list->insert(42);
        $list->insert(10);
        $list->insert(99);

        $this->assertTrue($list->contains(42));
        $this->assertTrue($list->contains(10));
        $this->assertTrue($list->contains(99));
        $this->assertFalse($list->contains(50));
    }

    /**
     * Test first and last operations.
     */
    public function testFirstAndLastOperations(): void
    {
        $list = new SortedLinkedList('int');

        // Empty list throws exceptions
        $this->expectException(EmptyList::class);
        $list->first();

        $list = new SortedLinkedList('int');
        $this->expectException(EmptyList::class);
        $list->last();

        // Single element
        $list = new SortedLinkedList('int');
        $list->insert(42);
        $this->assertEquals(42, $list->first());
        $this->assertEquals(42, $list->last());

        // Multiple elements
        $list->insert(10);
        $list->insert(99);
        $this->assertEquals(10, $list->first());
        $this->assertEquals(99, $list->last());
    }

    /**
     * Test count operations.
     */
    public function testCountOperations(): void
    {
        $list = new SortedLinkedList('int');

        $this->assertEquals(0, $list->count());
        $this->assertTrue($list->isEmpty());

        $list->insert(1);
        $this->assertEquals(1, $list->count());
        $this->assertFalse($list->isEmpty());

        $list->insert(2);
        $this->assertEquals(2, $list->count());

        $list->remove(1);
        $this->assertEquals(1, $list->count());
    }

    /**
     * Test string operations.
     */
    public function testStringOperations(): void
    {
        $list = new SortedLinkedList('string');

        $list->insert('zebra');
        $list->insert('apple');
        $list->insert('banana');

        $this->assertEquals(['apple', 'banana', 'zebra'], $list->toArray());
    }

    /**
     * Test custom comparator.
     */
    public function testCustomComparator(): void
    {
        $list = new SortedLinkedList('int', fn(int $a, int $b) => $b <=> $a);

        $list->insert(1);
        $list->insert(3);
        $list->insert(2);

        $this->assertEquals([3, 2, 1], $list->toArray());
    }

    /**
     * Test invalid value handling.
     */
    public function testInvalidValueHandling(): void
    {
        $list = new SortedLinkedList('string');

        $this->expectException(InvalidValue::class);
        $list->insert('   ');
    }

    /**
     * Test clear operation.
     */
    public function testClearOperation(): void
    {
        $list = new SortedLinkedList('int');
        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        $this->assertEquals(3, $list->count());

        $list->clear();

        $this->assertEquals(0, $list->count());
        $this->assertTrue($list->isEmpty());
        $this->assertEquals([], $list->toArray());
    }

    /**
     * Test duplicates policy (allow duplicates).
     */
    public function testDuplicatesPolicy(): void
    {
        $list = new SortedLinkedList('int');

        $list->insert(1);
        $list->insert(1);
        $list->insert(1);

        $this->assertEquals([1, 1, 1], $list->toArray());
        $this->assertEquals(3, $list->count());
    }

    /**
     * Test constructor type validation.
     */
    public function testConstructorTypeValidation(): void
    {
        $this->expectException(InvalidValue::class);
        $this->expectExceptionMessage("Type must be 'int' or 'string'");
        new SortedLinkedList('float');
    }

    /**
     * Test iterator functionality.
     */
    public function testIteratorFunctionality(): void
    {
        $list = new SortedLinkedList('int');
        $list->insert(3);
        $list->insert(1);
        $list->insert(2);

        $values = [];
        foreach ($list as $value) {
            $values[] = $value;
        }

        $this->assertEquals([1, 2, 3], $values);
    }

    /**
     * Test countable interface.
     */
    public function testCountableInterface(): void
    {
        $list = new SortedLinkedList('int');
        $list->insert(1);
        $list->insert(2);

        $this->assertEquals(2, count($list));
    }

    /**
     * Test fast path insertions.
     */
    public function testFastPathInsertions(): void
    {
        $list = new SortedLinkedList('int');

        // Insert at head (fast path)
        $list->insert(5);
        $list->insert(3);
        $this->assertEquals([3, 5], $list->toArray());

        // Insert at tail (fast path)
        $list->insert(7);
        $this->assertEquals([3, 5, 7], $list->toArray());
    }

    /**
     * Test comparator validation.
     */
    public function testComparatorValidation(): void
    {
        $this->expectException(InvalidValue::class);
        $this->expectExceptionMessage('Comparator must accept exactly two arguments');
        new SortedLinkedList('int', fn($a) => $a);
    }
}
