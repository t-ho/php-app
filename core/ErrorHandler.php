<?php

declare(strict_types=1);

namespace Core;

use ErrorException;
use Throwable;

class ErrorHandler
{
    public static bool $isLogFileCreated = false;

    public static function handleException(Throwable $exception): void
    {
        self::logError($exception);

        if (php_sapi_name() === 'cli') {
            self::renderCliError($exception);
        } else {
            self::renderErrorPage($exception);
        }
    }

    public static function handleError($level, $message, $file, $line): void
    {
        $exception = new ErrorException($message, 0, $level, $file, $line);

        self::handleException($exception);
    }

    private static function renderCliError(Throwable $exception): void
    {
        $isDebug = App::get('config')['app']['debug'] ?? false;

        $errorMessage = '';
        $trace = '';
        if ($isDebug) {
            $errorMessage = self::formatErrorMessage(
                $exception,
                "\033[31m[%s] Error:\033[0m %s: %s in %s on line %d" . PHP_EOL
            );
            $trace = $exception->getTraceAsString();
        } else {
            $errorMessage = "\033[31mAn unexpected error occurred. Please check error log for details.\033[0m"
                . PHP_EOL;
            $trace = '';
        }

        fwrite(STDERR, $errorMessage);
        if ($trace) {
            fwrite(STDERR, "Stack trace:" . PHP_EOL . $trace . PHP_EOL);
        }

        exit(1);
    }

    private static function renderErrorPage(Throwable $exception): void
    {
        $isDebug = App::get('config')['app']['debug'] ?? false;

        $errorMessage = '';
        $trace = '';
        if ($isDebug) {
            $errorMessage = self::formatErrorMessage(
                $exception,
                "[%s] Error: %s: %s in %s on line %d"
            );
            $trace = $exception->getTraceAsString();
        } else {
            $errorMessage = "We encountered an unexpected error while processing your request. Please try again later.";
            $trace = '';
        }

        http_response_code(500);
        echo View::render('errors/500', [
            'errorMessage' => $errorMessage,
            'trace' => $trace,
            'isDebug' => $isDebug,
        ], 'layouts/main');
        exit();
    }

    private static function logError(Throwable $exception): void
    {
        if (!self::$isLogFileCreated) {
            self::createLogFile();
            self::$isLogFileCreated = true;
        }

        $logMessage = self::formatErrorMessage(
            $exception,
            "[%s] Error: %s: %s in %s on line %d"
        );

        $logMessage .= PHP_EOL . "Stack Trace: " . $exception->getTraceAsString() . PHP_EOL;

        error_log($logMessage, 3, __DIR__ . '/../logs/error.log');
    }

    private static function formatErrorMessage(Throwable $exception, string $format): string
    {
        return sprintf(
            $format,
            date('Y-m-d H:i:s'),
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
    }

    private static function createLogFile(): void
    {
        $logDir = __DIR__ . '/../logs';
        $logFile = $logDir . '/error.log';

        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        if (!file_exists($logFile)) {
            // Create the file and set appropriate permissions
            file_put_contents($logFile, '');
            chmod($logFile, 0644);
        }
    }
}
