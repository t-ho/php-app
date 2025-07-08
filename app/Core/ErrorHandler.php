<?php

declare(strict_types=1);

namespace App\Core;

use ErrorException;
use Exception;
use Throwable;

class ErrorHandler
{
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
        $logMessage = self::formatErrorMessage(
            $exception,
            "[%s] Error: %s: %s in %s on line %d"
        );

        $logMessage .= PHP_EOL . "Stack Trace: " . $exception->getTraceAsString() . PHP_EOL;

        error_log($logMessage);

        // Try to log to dedicated unhandled log file
        $logFile = __DIR__ . '/../../storage/logs/unhandled-errors.log';
        $logDir = dirname($logFile);

        try {
            // Create directory if it doesn't exist
            if (!is_dir($logDir)) {
                if (!mkdir($logDir, 0777, true)) {
                    return;
                }
            }

            // Check if we can write to the directory
            if (!is_writable($logDir)) {
                return;
            }


            // Add separator for file logging
            $detailedEntry = $logMessage . PHP_EOL . str_repeat('-', 80) . PHP_EOL;

            file_put_contents($logFile, $detailedEntry, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            // Silently fail
        }
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
}
