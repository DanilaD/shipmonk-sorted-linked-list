<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use ShipMonk\SortedLinkedList\Commands\SortedLinkedListCommand;
use ShipMonk\SortedLinkedList\Services\SortedLinkedListService;

// Create service and command
$service = new SortedLinkedListService();
$command = new SortedLinkedListCommand($service);
$command->run();
