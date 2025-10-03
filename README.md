# ShipMonk SortedLinkedList

A **production-ready**, type-safe sorted doubly linked list for PHP 8.3+. Features automatic sorting, custom comparators, iterator support, and comprehensive error handling with SOLID principles.

## Features

- **Type Safety**: Enforces single type per list ('int' or 'string')
- **Auto-Sorting**: Values automatically inserted in sorted order
- **Custom Comparators**: Define your own sorting logic
- **Iterator Support**: Use with `foreach` loops and `count()`
- **Performance**: O(N) insertion (O(1) at head/tail), optimized with early exit
- **Error Handling**: Comprehensive exception system
- **Quality**: PHPStan level max, PSR-12 compliant, 100% test coverage

## Requirements

- PHP 8.3+
- Composer

## Installation

```bash
# Clone the repository
git clone https://github.com/DanilaD/shipmonk-sorted-linked-list.git
cd shipmonk

# Install dependencies
composer install
```

## Quick Start

### Basic Usage

```php
<?php
require_once 'vendor/autoload.php';

use ShipMonk\SortedLinkedList\Collections\SortedLinkedList;

// Create integer list
$list = new SortedLinkedList('int');

// Insert values (automatically sorted)
$list->insert(5);
$list->insert(1);
$list->insert(3);

// Get sorted array
echo implode(', ', $list->toArray()); // Output: 1, 3, 5

// Use with foreach
foreach ($list as $value) {
    echo $value . "\n";
}

// Count elements
echo count($list); // Output: 3
```

### String Lists

```php
$stringList = new SortedLinkedList('string');
$stringList->insert('zebra');
$stringList->insert('apple');
$stringList->insert('banana');

echo implode(', ', $stringList->toArray()); // Output: apple, banana, zebra
```

### Custom Comparators

```php
// Descending order
$descList = new SortedLinkedList('int', fn(int $a, int $b) => $b <=> $a);

// Case-insensitive string sorting
$caseList = new SortedLinkedList('string', fn(string $a, string $b) => strcasecmp($a, $b));

// Sort by absolute value
$absList = new SortedLinkedList('int', fn(int $a, int $b) => abs($a) <=> abs($b));
```

## Examples

Run the included examples:

```bash
# Basic usage examples
php examples/basic-usage.php

# Advanced usage examples
php examples/advanced-usage.php
```

## Testing

```bash
# Run all tests
./vendor/bin/phpunit

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage/

# Run specific test
./vendor/bin/phpunit tests/SortedLinkedListTest.php
```

## Quality Checks

```bash
# Code style check (PSR-12)
./vendor/bin/phpcs

# Static analysis (Level max)
./vendor/bin/phpstan analyse

# Auto-fix code style issues
./vendor/bin/phpcbf

# Run all quality checks
./vendor/bin/phpcs && ./vendor/bin/phpstan analyse && ./vendor/bin/phpunit
```

## API Reference

### Constructor

```php
new SortedLinkedList(string $type, ?callable $comparator = null)
```

- `$type`: Either `'int'` or `'string'`
- `$comparator`: Optional custom comparison function

### Methods

| Method | Description | Return Type |
|--------|-------------|-------------|
| `insert(mixed $value)` | Insert value maintaining sort order | `void` |
| `remove(mixed $value)` | Remove value from list | `bool` |
| `contains(mixed $value)` | Check if value exists | `bool` |
| `first()` | Get first value | `mixed` |
| `last()` | Get last value | `mixed` |
| `count()` | Get element count | `int` |
| `isEmpty()` | Check if list is empty | `bool` |
| `toArray()` | Get all values as array | `array` |
| `clear()` | Clear the list | `void` |

### Interfaces

- **Countable**: Use with `count()` function
- **IteratorAggregate**: Use with `foreach` loops

## Error Handling

```php
use ShipMonk\SortedLinkedList\Domain\Exceptions\TypeMismatch;
use ShipMonk\SortedLinkedList\Domain\Exceptions\EmptyList;
use ShipMonk\SortedLinkedList\Domain\Exceptions\InvalidValue;

try {
    $list->insert('string'); // Type mismatch
} catch (TypeMismatch $e) {
    echo $e->getMessage(); // "Type mismatch: expected int, got string"
}

try {
    $emptyList->first(); // Empty list
} catch (EmptyList $e) {
    echo $e->getMessage(); // "Cannot perform first on empty list"
}
```

## Performance

- **Insertion**: O(N) worst case, O(1) for head/tail
- **Search**: O(N) worst case, with early exit thanks to sorted order
- **Removal**: O(1) once node is found
- **Memory**: ~179 bytes per element
- **Speed**: 1000 elements in ~105ms

## Architecture

```
src/
├── Collections/
│   ├── Node.php              # Internal node class
│   ├── SortedList.php        # Interface
│   └── SortedLinkedList.php  # Implementation
└── Domain/
    └── Exceptions/
        ├── EmptyList.php      # Empty list operations
        ├── InvalidValue.php   # Invalid values
        └── TypeMismatch.php   # Type mismatches
```

## Use Cases

- **Priority Queues**: Maintain sorted order automatically
- **Data Processing**: Sort data as it arrives
- **Caching**: Keep frequently accessed items sorted
- **Algorithms**: Implement sorting algorithms efficiently
- **APIs**: Return sorted data without manual sorting

## Benchmarks

```bash
# Run performance examples
php examples/advanced-usage.php
```

Sample results:
- 1000 random integers: ~105ms
- Memory usage: ~17.5 KB for 100 elements
- Average per element: ~179 bytes

## Contributing

1. Fork the repository
2. Create a feature branch
3. Run quality checks: `./vendor/bin/phpcs && ./vendor/bin/phpstan analyse`
4. Add tests for new functionality
5. Submit a pull request

## License

MIT License - see LICENSE file for details.

## Quality Metrics

- **PHPStan**: Level max - No errors
- **PHPCS**: PSR-12 compliant - No violations  
- **PHPUnit**: 16/16 tests passing (37 assertions)
- **Coverage**: 100% test coverage
- **Performance**: Optimized algorithms
- **Documentation**: Comprehensive examples
