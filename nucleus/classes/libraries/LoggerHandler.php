<?php

namespace Nucleus\Libraries;

class LoggerHandler
{
    private static ?string $logFile = null;

    /**
     * Ініціалізує логгер з файлом для запису (опціонально).
     * @param string|null $logFile Шлях до файлу логів.
     */

    public static function init(string $logFile = null): void
    {
        self::$logFile = $logFile ?? ROOT . '/nucleus/data/logs/application.log';
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

        if (self::$logFile) {
            file_put_contents(self::$logFile, $formattedMessage . PHP_EOL, FILE_APPEND);
        } else {
            error_log($formattedMessage);
        }

        if (strtoupper($level) === 'CRITICAL') {
            die($message);
        }
    }

    /**
     * Записує критичну помилку та блокує виконання.
     * @param string $message Текст повідомлення.
     */

    public static function critical(string $message): void
    {
        self::log($message, 'CRITICAL');
    }

    /**
     * Записує повідомлення рівня WARNING.
     * @param string $message Текст повідомлення.
     */

    public static function warning(string $message): void
    {
        self::log($message, 'WARNING');
    }

    /**
     * Записує інформаційне повідомлення.
     * @param string $message Текст повідомлення.
     */

    public static function info(string $message): void
    {
        self::log($message, 'INFO');
    }

    /**
     * Записує повідомлення рівня DEBUG.
     * @param string $message Текст повідомлення.
     */

    public static function debug(string $message): void
    {
        self::log($message, 'DEBUG');
    }

    /**
     * Записывает сообщение уровня ERROR.
     * @param string $message Текст сообщения.
     */
    
    public static function error(string $message): void
    {
        self::log($message, 'ERROR');
    }
}

class ErrorHandler
{
    /**
     * Ініціалізує обробник помилок та реєструє функцію обробки помилок.
     */

    public static function init(): void
    {
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);

        LoggerHandler::init();
    }

    /**
     * Обробка помилок PHP.
     * @param int $errno Код помилки.
     * @param string $errstr Опис помилки.
     * @param string $errfile Файл, де виникла помилка.
     * @param int $errline Номер рядка, де виникла помилка.
     */

    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): void
    {
        $errorMessage = "Error [$errno]: $errstr in $errfile on line $errline";
        LoggerHandler::log($errorMessage, 'ERROR');

        if (!(error_reporting() & $errno)) {
            return;
        }

        throw new \ErrorException($errorMessage, $errno, 0, $errfile, $errline);
    }

    /**
     * Обробка винятків.
     * @param \Throwable $exception Об'єкт винятку.
     */

    public static function handleException(\Throwable $exception): void
    {
        $errorMessage = "Exception: " . $exception->getMessage() . "\nStack trace:\n" . $exception->getTraceAsString();
        LoggerHandler::log($errorMessage, 'CRITICAL');

        // Вивід користувачу загального повідомлення при помилці
        echo "An error occurred. Please try again later.";
    }

    /**
     * Обробка завершення скрипту для виявлення фатальних помилок.
     */

    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null && ($error['type'] & (E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR))) {
            $errorMessage = "Fatal Error: " . $error['message'] . " in " . $error['file'] . " on line " . $error['line'];
            LoggerHandler::log($errorMessage, 'CRITICAL');
        }
    }
}
