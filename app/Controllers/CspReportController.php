<?php

namespace App\Controllers;

use App\Core\BaseController;
use Exception;

class CspReportController extends BaseController
{
    /**
     * Handle CSP violation reports
     */
    public function report(): void
    {
        // Get raw JSON input
        $input = file_get_contents('php://input');

        if (empty($input)) {
            $this->json(['error' => 'No report data'], 400);
            return;
        }

        try {
            $report = json_decode($input, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->json(['error' => 'Invalid JSON'], 400);
                return;
            }

            // Log the CSP violation
            $this->logCspViolation($report);

            // Return success response (204 No Content)
            http_response_code(204);
            exit;
        } catch (Exception $e) {
            error_log("CSP Report Error: " . $e->getMessage());
            $this->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Log CSP violation to file and error log
     */
    private function logCspViolation(array $report): void
    {
        if (!isset($report['csp-report'])) {
            return;
        }

        $violation = $report['csp-report'];

        // Create readable log entry
        $logEntry = sprintf(
            "[CSP VIOLATION] %s | Blocked: %s | Directive: %s | Source: %s | Line: %s | User-Agent: %s",
            date('Y-m-d H:i:s'),
            $violation['blocked-uri'] ?? 'unknown',
            $violation['violated-directive'] ?? 'unknown',
            $violation['source-file'] ?? 'unknown',
            $violation['line-number'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        );

        // Always log to PHP error log (this always works)
        error_log($logEntry);

        // Try to log to dedicated CSP log file
        $logFile = __DIR__ . '/../../storage/logs/csp-violations.log';
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

            // Write to log file
            $detailedEntry = sprintf(
                "%s - [CSP VIOLATION]\nBlocked URI: %s\nViolated Directive: %s\nSource File: %s\nLine Number: %s\nDocument URI: %s\nUser Agent: %s\nFull Report: %s\n%s\n",
                date('Y-m-d H:i:s'),
                $violation['blocked-uri'] ?? 'unknown',
                $violation['violated-directive'] ?? 'unknown',
                $violation['source-file'] ?? 'unknown',
                $violation['line-number'] ?? 'unknown',
                $violation['document-uri'] ?? 'unknown',
                $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                json_encode($violation, JSON_PRETTY_PRINT),
                str_repeat('-', 80)
            );

            file_put_contents($logFile, $detailedEntry, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            // Silently fail - CSP violations are logged to error_log above
        }
    }
}
