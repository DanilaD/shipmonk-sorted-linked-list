<?php

/**
 * Simple exception for SortedLinkedList.
 *
 * @category   ShipMonk
 * @package    SortedLinkedList
 * @author     ShipMonk Team <dev@shipmonk.com>
 * @license    MIT
 * @link       https://github.com/shipmonk/sorted-linked-list
 */

declare(strict_types=1);

namespace ShipMonk\SortedLinkedList\Exceptions;

use Exception;

/**
 * Simple exception for SortedLinkedList operations.
 */
class SortedLinkedListException extends Exception
{
    /**
     * Create a type mismatch exception.
     *
     * @param string $expectedType The expected type
     * @param string $actualType   The actual type
     *
     * @return self
     */
    public static function typeMismatch(string $expectedType, string $actualType): self
    {
        return new self('Type mismatch: values must be of the same type');
    }

    /**
     * Create an input too long exception.
     *
     * @param int $maxLength Maximum allowed length
     *
     * @return self
     */
    public static function inputTooLong(int $maxLength): self
    {
        return new self("Input too long: maximum {$maxLength} characters allowed");
    }

    /**
     * Create an empty input exception.
     *
     * @return self
     */
    public static function emptyInput(): self
    {
        return new self('Input cannot be empty');
    }

    /**
     * Create an array size limit exception.
     *
     * @param int $maxSize Maximum allowed array size
     *
     * @return self
     */
    public static function arraySizeLimit(int $maxSize): self
    {
        return new self("Array size limit exceeded: maximum {$maxSize} values allowed");
    }

    /**
     * Create a string too long exception.
     *
     * @param int $maxLength Maximum allowed string length
     *
     * @return self
     */
    public static function stringTooLong(int $maxLength): self
    {
        return new self("String too long: maximum {$maxLength} characters allowed");
    }

    /**
     * Create an empty value exception.
     *
     * @return self
     */
    public static function emptyValue(): self
    {
        return new self('Value cannot be empty');
    }

    /**
     * Create a whitespace-only value exception.
     *
     * @return self
     */
    public static function whitespaceOnlyValue(): self
    {
        return new self('Value cannot be whitespace only');
    }

    /**
     * Create an integer out of range exception.
     *
     * @param int $min Minimum allowed value
     * @param int $max Maximum allowed value
     *
     * @return self
     */
    public static function integerOutOfRange(int $min, int $max): self
    {
        return new self("Integer out of range: must be between {$min} and {$max}");
    }

    /**
     * Create an invalid command exception.
     *
     * @param string $command The invalid command
     *
     * @return self
     */
    public static function invalidCommand(string $command): self
    {
        return new self("Invalid command: {$command}");
    }
}
