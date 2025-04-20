<?php
namespace app\utils;

class Logger {
    public static function logError($message) {
        $logFile = __DIR__ . '/../../logs/error_log.txt';
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[{$timestamp}] ERROR: {$message}\n";
        file_put_contents($logFile, $formattedMessage, FILE_APPEND);
    }
}
?>