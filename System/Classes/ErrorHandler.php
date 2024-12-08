<?php

namespace System\Classes;

use System\Classes\LoggerHandler;

class ErrorHandler
{
    /**
     * Ініціалізує обробник помилок та реєструє функцію обробки помилок.
     */
    public static function init(): void
    {
        // Реєстрація обробника помилок для PHP
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);

        // Ініціалізація логера
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
        LoggerHandler::error($errorMessage);
        
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
        LoggerHandler::critical($errorMessage);
        echo "An error occurred. Please try again later.";
    }

    /**
     * Обробка завершення скрипту для виявлення фатальних помилок.
     */
    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null) {
            $errorMessage = "Fatal Error: " . $error['message'] . " in " . $error['file'] . " on line " . $error['line'];
            LoggerHandler::critical($errorMessage);
        }
    }
}
