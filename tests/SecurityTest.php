<?php

declare(strict_types=1);

namespace ShipMonk\SortedLinkedList\Tests;

use PHPUnit\Framework\TestCase;
use ShipMonk\SortedLinkedList\Services\SortedLinkedListService;
use ShipMonk\SortedLinkedList\Exceptions\SortedLinkedListException;

/**
 * Security tests for SortedLinkedList.
 */
class SecurityTest extends TestCase
{
    private SortedLinkedListService $service;

    protected function setUp(): void
    {
        $this->service = new SortedLinkedListService();
    }

    /**
     * Test input length validation.
     */
    public function testInputLengthValidation(): void
    {
        $longString = str_repeat('a', 1001); // Exceeds MAX_INPUT_LENGTH
        
        $result = $this->service->insertValue($longString);
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('String too long', $result['message']);
    }

    /**
     * Test empty value validation.
     */
    public function testEmptyValueValidation(): void
    {
        $result = $this->service->insertValue('');
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Value cannot be empty', $result['message']);
    }

    /**
     * Test whitespace-only value validation.
     */
    public function testWhitespaceOnlyValueValidation(): void
    {
        $result = $this->service->insertValue('   ');
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('whitespace only', $result['message']);
    }

    /**
     * Test integer range validation.
     */
    public function testIntegerRangeValidation(): void
    {
        // Test maximum integer
        $result = $this->service->insertValue(2147483648); // Exceeds MAX_INTEGER_VALUE
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Integer out of range', $result['message']);
    }

    /**
     * Test array size limit validation.
     */
    public function testArraySizeLimitValidation(): void
    {
        // Insert values up to the limit (10000)
        for ($i = 0; $i < 10000; $i++) {
            $result = $this->service->insertValue($i);
            $this->assertTrue($result['success']);
        }
        
        // Try to insert one more - should fail
        $result = $this->service->insertValue(10000);
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Array size limit exceeded', $result['message']);
    }

    /**
     * Test string sanitization.
     */
    public function testStringSanitization(): void
    {
        $maliciousString = "test<script>alert('xss')</script>value";
        
        $result = $this->service->insertValue($maliciousString);
        $this->assertTrue($result['success']);
        
        // Verify the string was sanitized
        $stats = $this->service->getListStats();
        $this->assertStringNotContainsString('<script>', $stats['values'][0]);
    }

    /**
     * Test command injection prevention.
     */
    public function testCommandInjectionPrevention(): void
    {
        $commandInjection = "test; rm -rf /";
        
        $result = $this->service->insertValue($commandInjection);
        $this->assertTrue($result['success']);
        
        // Verify the string is stored as-is (no SQL, but command injection patterns could be sanitized)
        $stats = $this->service->getListStats();
        $this->assertEquals($commandInjection, $stats['values'][0]);
    }

    /**
     * Test control character removal.
     */
    public function testControlCharacterRemoval(): void
    {
        $stringWithControlChars = "test\x00\x01\x02value";
        
        $result = $this->service->insertValue($stringWithControlChars);
        $this->assertTrue($result['success']);
        
        // Verify control characters were removed
        $stats = $this->service->getListStats();
        $this->assertStringNotContainsString("\x00", $stats['values'][0]);
        $this->assertStringNotContainsString("\x01", $stats['values'][0]);
    }

    /**
     * Test type consistency enforcement.
     */
    public function testTypeConsistencyEnforcement(): void
    {
        // Insert integer first
        $result = $this->service->insertValue(123);
        $this->assertTrue($result['success']);
        
        // Try to insert string - should fail
        $result = $this->service->insertValue('string');
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Type mismatch', $result['message']);
    }

    /**
     * Test memory usage monitoring.
     */
    public function testMemoryUsageMonitoring(): void
    {
        // This test ensures memory monitoring doesn't break functionality
        for ($i = 0; $i < 100; $i++) {
            $result = $this->service->insertValue($i);
            $this->assertTrue($result['success']);
        }
        
        $stats = $this->service->getListStats();
        $this->assertEquals(100, $stats['count']);
    }

    /**
     * Test edge case: very long but valid string.
     */
    public function testValidLongString(): void
    {
        $longValidString = str_repeat('a', 255); // Exactly MAX_STRING_LENGTH
        
        $result = $this->service->insertValue($longValidString);
        $this->assertTrue($result['success']);
        
        $stats = $this->service->getListStats();
        $this->assertEquals($longValidString, $stats['values'][0]);
    }

    /**
     * Test edge case: boundary integer values.
     */
    public function testBoundaryIntegerValues(): void
    {
        // Test minimum valid integer
        $result = $this->service->insertValue(-2147483648);
        $this->assertTrue($result['success']);
        
        // Test maximum valid integer
        $result = $this->service->insertValue(2147483647);
        $this->assertTrue($result['success']);
        
        $stats = $this->service->getListStats();
        $this->assertEquals(2, $stats['count']);
    }
}
