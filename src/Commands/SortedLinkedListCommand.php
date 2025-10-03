<?php

/**
 * Console command for interacting with SortedLinkedList.
 *
 * @category   ShipMonk
 * @package    SortedLinkedList
 * @author     ShipMonk Team <dev@shipmonk.com>
 * @license    MIT
 * @link       https://github.com/shipmonk/sorted-linked-list
 */

declare(strict_types=1);

namespace ShipMonk\SortedLinkedList\Commands;

use ShipMonk\SortedLinkedList\Services\SortedLinkedListService;
use ShipMonk\SortedLinkedList\Security\SecurityValidator;
use InvalidArgumentException;

/**
 * Console command for interacting with SortedLinkedList.
 *
 * Demonstrates Command Pattern and CLI interface.
 */
class SortedLinkedListCommand
{
    private SortedLinkedListService $service;
    private SecurityValidator $securityValidator;

    /**
     * Constructor.
     *
     * @param SortedLinkedListService $service The service to use
     * @param SecurityValidator $securityValidator The security validator to use
     */
    public function __construct(
        SortedLinkedListService $service,
        SecurityValidator $securityValidator = new SecurityValidator()
    ) {
        $this->service = $service;
        $this->securityValidator = $securityValidator;
    }

    /**
     * Run the interactive command.
     *
     * @return void
     */
    public function run(): void
    {
        echo "=== ShipMonk SortedLinkedList CLI ===\n";
        echo "Commands: insert <value>, remove <value>, contains <value>, stats, clear, quit\n\n";

        while (true) {
            $input = readline("> ");
            if ($input === false) {
                $input = '';
            }
            
            try {
                $sanitizedInput = $this->securityValidator->validateInput($input);
                
                if ($sanitizedInput === 'quit') {
                    echo "Goodbye!\n";
                    break;
                }

                $this->handleCommand($sanitizedInput);
            } catch (\Exception $e) {
                echo "✗ " . $e->getMessage() . "\n";
            }
        }
    }

    /**
     * Handle user command input.
     *
     * @param string $input The user input
     *
     * @return void
     */
    private function handleCommand(string $input): void
    {
        $parts = explode(' ', trim($input));
        $command = $parts[0] ?? '';
        $value = $parts[1] ?? null;

        try {
            $validatedCommand = $this->securityValidator->validateCommand($command);
            
            match ($validatedCommand) {
                'insert' => $this->handleValueCommand('insert', $value),
                'remove' => $this->handleValueCommand('remove', $value),
                'contains' => $this->handleValueCommand('contains', $value),
                'stats' => $this->handleStats(),
                'clear' => $this->handleClear(),
                default => $this->handleUnknown($validatedCommand)
            };
        } catch (\Exception $e) {
            echo "✗ " . $e->getMessage() . "\n";
        }
    }

    /**
     * Handle commands that require a value parameter.
     *
     * @param string      $command The command name
     * @param string|null $value   The value parameter
     *
     * @return void
     */
    private function handleValueCommand(string $command, ?string $value): void
    {
        if ($value === null) {
            echo "Usage: {$command} <value>\n";
            return;
        }

        try {
            $parsedValue = $this->parseValue($value);
            $result = $this->service->{$command . 'Value'}($parsedValue);

            echo $this->formatResult($result);
        } catch (InvalidArgumentException $e) {
            echo "✗ " . $e->getMessage() . "\n";
        }
    }

    /**
     * Handle stats command.
     *
     * @return void
     */
    private function handleStats(): void
    {
        $stats = $this->service->getListStats();

        echo "List Statistics:\n";
        echo "  Count: {$stats['count']}\n";
        echo "  Empty: " . ($stats['isEmpty'] ? 'Yes' : 'No') . "\n";
        echo "  Type: " . ($stats['valueType'] ?? 'None') . "\n";
        echo "  First: " . ($stats['first'] ?? 'None') . "\n";
        echo "  Last: " . ($stats['last'] ?? 'None') . "\n";
        echo "  Values: [" . implode(', ', $stats['values']) . "]\n";
    }

    /**
     * Handle clear command.
     *
     * @return void
     */
    private function handleClear(): void
    {
        $result = $this->service->clearList();

        echo $this->formatResult($result);
    }

    /**
     * Handle unknown command.
     *
     * @param string $command The unknown command
     *
     * @return void
     */
    private function handleUnknown(string $command): void
    {
        echo "Unknown command: {$command}\n";
        echo "Available commands: insert, remove, contains, stats, clear, quit\n";
    }

    /**
     * Parse string value to int or string with validation.
     *
     * @param string $value The value to parse
     *
     * @return string|int
     *
     * @throws InvalidArgumentException If value is empty
     */
    private function parseValue(string $value): string|int
    {
        $trimmed = trim($value);

        if ($trimmed === '') {
            throw new InvalidArgumentException('Value cannot be empty');
        }

        return is_numeric($trimmed) ? (int) $trimmed : $trimmed;
    }

    /**
     * Format result message consistently.
     *
     * @param array{success: bool, message: string} $result The result array
     *
     * @return string Formatted message
     */
    private function formatResult(array $result): string
    {
        return ($result['success'] ? "✓ " : "✗ ") . $result['message'] . "\n";
    }
}
