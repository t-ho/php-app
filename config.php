<?php

return [
  'app' => [
    'name' => $_ENV['APP_NAME'] ?? 'PHP App',
    'debug' => $_ENV['APP_DEBUG'] ?? true,
    'env' => $_ENV['APP_ENV'] ?? 'development',
  ],
  'database' => [
    'driver' => 'mysql',
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'dbname' => $_ENV['DB_NAME'] ?? 'testing_db',
    'username' => $_ENV['DB_USER'] ?? '',
    'password' => $_ENV['DB_PASS'] ?? '',
    'port' => $_ENV['DB_PORT'] ?? 3306,
  ]
];
