<?php

namespace LiveCMS\Libraries;

use LiveCMS\Constants;

/**
 * Клас для обробки логування.
 * Використовується для запису логів різних рівнів у файл.
 */

class LoggerHandler
{
    private static ?string $logFile = null;

    /**
     * Ініціалізує логгер з опційним файлом для запису.
     * @param string|null $logFile Шлях до файлу логів.
     */

    public static function init(string $logFile = null): void
    {
        # Встановлює шлях до файлу логів, якщо він не переданий, використовується значення за замовчуванням.
        self::$logFile = $logFile ?? ROOT . '/nucleus/data/logs/' . date('Y-m-d', Constants::get('TM')) . '.log';
    }

    /**
     * Записує повідомлення у лог.
     * @param string $message Текст повідомлення.
     * @param string $level Рівень повідомлення (DEBUG, INFO, WARNING, ERROR, CRITICAL).
     */
    public static function log(string $message, string $level = 'INFO'): void
    {
        $timestamp = date('Y-m-d H:i:s', Constants::get('TM'));
        $formattedMessage = "[$timestamp] [$level] $message";

        # Записує повідомлення у файл логів, якщо він визначений, або використовує error_log().
        if (self::$logFile) {
            file_put_contents(self::$logFile, $formattedMessage . PHP_EOL, FILE_APPEND);
        } else {
            error_log($formattedMessage);
        }

        # Завершує виконання скрипта при критичній помилці.
        if (strtoupper($level) === 'CRITICAL') {
            die($message);
        }
    }

    /**
     * Записує критичну помилку та блокує виконання.
     * @param string $message Текст критичної помилки.
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
     * Записує повідомлення рівня ERROR.
     * @param string $message Текст повідомлення.
     */

    private static function error(string $message): void
    {
        self::log($message, 'ERROR');
    }
}

/**
 * Клас для обробки помилок і виключень.
 * Використовується для реєстрації обробників помилок та виключень.
 */

class ErrorHandler
{
    /**
     * Ініціалізує обробник помилок та реєструє функцію обробки помилок.
     */

    public static function init(): void
    {
        # Реєстрація обробників помилок та виключень.
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);

        # Ініціалізація логера.
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

        # Вивід користувачу загального повідомлення при помилці.
        echo "Виникла помилка. Будь ласка, спробуйте знову пізніше.";
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
