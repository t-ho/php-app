<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use Core\App;

$db = App::get('database');

$schemaFile = __DIR__ . '/../database/schema.sql';

$sql = file_get_contents($schemaFile);

if ($sql === false) {
    throw new RuntimeException("Failed to read schema file.");
}

// Strip -- line comments
$sql = preg_replace('/--.*(\n|$)/', '', $sql);

// Strip /* */ block comments
$sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

// Split by semicolon
$parts = array_filter(array_map('trim', explode(';', $sql)));

foreach ($parts as $part) {
    if (!empty($part)) {
        $db->query($part);
    }
}

echo "Database schema loaded successfully." . PHP_EOL;
