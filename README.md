# ShipMonk SortedLinkedList

A small, type-safe sorted linked list for PHP 8.3. It holds only a single scalar type (integer or string), maintains sort order on insert, and implements `Iterator` and `Countable`. A thin Service + Controller + CLI demonstrate SOLID separation without over‑engineering.

## Requirements
- PHP 8.3+
- Composer

## Install (local)
```bash
composer install
```

## Quick usage
```php
<?php
require __DIR__ . '/vendor/autoload.php';

use ShipMonk\SortedLinkedList\Repositories\InMemorySortedLinkedListRepository;
use ShipMonk\SortedLinkedList\Services\SortedLinkedListService;
use ShipMonk\SortedLinkedList\Controllers\SortedLinkedListController;

$repo = new InMemorySortedLinkedListRepository();
$service = new SortedLinkedListService($repo);
$controller = new SortedLinkedListController($service);

$controller->insert(3);
$controller->insert(1);
$controller->insert(2);

$stats = $controller->getStats();
print_r($stats['values']); // [1, 2, 3]

$controller->contains(2);   // ['success' => true,  'message' => 'Value 2 found']
$controller->remove(2);     // ['success' => true,  'message' => 'Value 2 removed successfully']
```

## CLI
```bash
php cli.php
```
Commands:
- insert <value>
- remove <value>
- contains <value>
- stats
- clear
- quit

## Quality checks
```bash
composer test     # PHPUnit
composer phpstan  # Static analysis (level 8, project-configured)
```

## License
MIT
