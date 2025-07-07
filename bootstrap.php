<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

// Load helper functions before error handlers
foreach (glob(__DIR__ . '/app/Helpers/*.php') as $file) {
    require_once $file;
}

use App\Core\App;
use App\Core\Database;
use App\Core\ErrorHandler;
use App\Helpers\ViteHelper;

set_exception_handler([ErrorHandler::class, 'handleException']);
set_error_handler([ErrorHandler::class, 'handleError']);

$config = require_once __DIR__ . '/config.php';

App::bind('config', $config);
App::bind('database', new Database($config['database']));
App::bind('vite', new ViteHelper());
