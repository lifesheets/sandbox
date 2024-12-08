<?php

namespace System\Classes;

class LoggerHandler
{
    private static ?string $logFile = null;

    /**
     * Ініціалізує логгер з файлом для запису (опціонально).
     * @param string|null $logFile Шлях до файлу логів.
     */
    public static function init(string $logFile = null): void
    {
        self::$logFile = $logFile ?? ROOT . '/System/Logs/Application.log';
    }

    /**
     * Записує повідомлення у лог.
     * @param string $message Текст повідомлення.
     * @param string $level Рівень повідомлення (ERROR, WARNING, INFO, DEBUG, CRITICAL).
     */
    public static function log(string $message, string $level = 'INFO'): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[$timestamp] [$level] $message";

        // Запис у файл логів
        if (self::$logFile) {
            file_put_contents(self::$logFile, $formattedMessage . PHP_EOL, FILE_APPEND);
        } else {
            error_log($formattedMessage);
        }

        // Виводимо повідомлення на екран при критичних помилках
        if (strtoupper($level) === 'CRITICAL') {
            die("CRITICAL ERROR: $message");
        }
    }

    /**
     * Записує критичну помилку та блокує виконання.
     * @param string $message Текст повідомлення.
     */
    private static function critical(string $message): void
    {
        self::log($message, 'CRITICAL');
    }

    /**
     * Записує повідомлення рівня WARNING.
     * @param string $message Текст повідомлення.
     */
    private static function warning(string $message): void
    {
        self::log($message, 'WARNING');
    }

    /**
     * Записує інформаційне повідомлення.
     * @param string $message Текст повідомлення.
     */
    private static function info(string $message): void
    {
        self::log($message, 'INFO');
    }

    /**
     * Записує повідомлення рівня DEBUG.
     * @param string $message Текст повідомлення.
     */
    private static function debug(string $message): void
    {
        self::log($message, 'DEBUG');
    }

    /**
     * Записує помилку у форматі JSON (запис у зовнішні системи або для тестування).
     * @param string $message Текст повідомлення.
     */

    public static function logJson(string $message, string $level = 'INFO'): void
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => $level,
            'message' => $message
        ];
        $formattedMessage = json_encode($logEntry, JSON_PRETTY_PRINT);
        if (self::$logFile) {
            file_put_contents(self::$logFile, $formattedMessage . PHP_EOL, FILE_APPEND);
        }
    }
}
