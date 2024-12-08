<?php

namespace System\Classes;

class Autoloader
{
    public static function register(): void
    {
        spl_autoload_register(function ($className) {
            $filePath = str_replace('\\', '/', $className) . '.php';
            if (file_exists($filePath)) {
                require_once $filePath;
            } else {
                \System\Classes\ErrorHandler::critical("Клас $className не знайдено. Очікуваний шлях: $filePath");
                throw new \Exception("Клас $className не знайдено. Очікуваний шлях: $filePath");
            }
        });
    }
}
