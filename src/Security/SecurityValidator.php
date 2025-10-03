<?php

/**
 * Simple security validator for SortedLinkedList.
 *
 * @category   ShipMonk
 * @package    SortedLinkedList
 * @author     ShipMonk Team <dev@shipmonk.com>
 * @license    MIT
 * @link       https://github.com/shipmonk/sorted-linked-list
 */

declare(strict_types=1);

namespace ShipMonk\SortedLinkedList\Security;

use ShipMonk\SortedLinkedList\Exceptions\SortedLinkedListException;

/**
 * Simple security validator for input validation.
 */
class SecurityValidator
{
    private const MAX_INPUT_LENGTH = 1000;
    private const MAX_ARRAY_SIZE = 10000;
    private const MAX_STRING_LENGTH = 255;
    private const MAX_INTEGER_VALUE = 2147483647;
    private const MIN_INTEGER_VALUE = -2147483648;

    /**
     * Validate and sanitize user input.
     *
     * @param string $input The raw user input
     *
     * @return string The sanitized input
     *
     * @throws SortedLinkedListException If input is invalid
     */
    public function validateInput(string $input): string
    {
        if (strlen($input) > self::MAX_INPUT_LENGTH) {
            throw SortedLinkedListException::inputTooLong(self::MAX_INPUT_LENGTH);
        }

        $sanitized = str_replace(["\0", "\r"], '', $input);
        $sanitized = trim($sanitized);
        
        if ($sanitized === '') {
            throw SortedLinkedListException::emptyInput();
        }

        return $sanitized;
    }

    /**
     * Validate value for insertion.
     *
     * @param string|int $value The value to validate
     * @param int $currentCount Current array size
     *
     * @return string|int The validated value
     *
     * @throws SortedLinkedListException If validation fails
     */
    public function validateValue(string|int $value, int $currentCount): string|int
    {
        if ($currentCount >= self::MAX_ARRAY_SIZE) {
            throw SortedLinkedListException::arraySizeLimit(self::MAX_ARRAY_SIZE);
        }

        if (is_string($value)) {
            return $this->validateString($value);
        }

        return $this->validateInteger($value);
    }

    /**
     * Validate string value.
     *
     * @param string $value The string to validate
     *
     * @return string The validated string
     *
     * @throws SortedLinkedListException If validation fails
     */
    private function validateString(string $value): string
    {
        if (strlen($value) > self::MAX_STRING_LENGTH) {
            throw SortedLinkedListException::stringTooLong(self::MAX_STRING_LENGTH);
        }

        if ($value === '') {
            throw SortedLinkedListException::emptyValue();
        }

        if (trim($value) === '') {
            throw SortedLinkedListException::whitespaceOnlyValue();
        }

        return $this->sanitizeString($value);
    }

    /**
     * Validate integer value.
     *
     * @param int $value The integer to validate
     *
     * @return int The validated integer
     *
     * @throws SortedLinkedListException If validation fails
     */
    private function validateInteger(int $value): int
    {
        if ($value > self::MAX_INTEGER_VALUE || $value < self::MIN_INTEGER_VALUE) {
            throw SortedLinkedListException::integerOutOfRange(
                self::MIN_INTEGER_VALUE,
                self::MAX_INTEGER_VALUE
            );
        }

        return $value;
    }

    /**
     * Sanitize string by removing dangerous characters.
     *
     * @param string $value The string to sanitize
     *
     * @return string The sanitized string
     */
    private function sanitizeString(string $value): string
    {
        $sanitized = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
        $sanitized = str_replace(['<script', '</script', 'javascript:', 'vbscript:'], '', $sanitized);
        
        return $sanitized;
    }

    /**
     * Validate command input.
     *
     * @param string $command The command to validate
     *
     * @return string The validated command
     *
     * @throws SortedLinkedListException If command is invalid
     */
    public function validateCommand(string $command): string
    {
        $allowedCommands = ['insert', 'remove', 'contains', 'stats', 'clear', 'quit'];
        
        if (!in_array($command, $allowedCommands, true)) {
            throw SortedLinkedListException::invalidCommand($command);
        }

        return $command;
    }
}
