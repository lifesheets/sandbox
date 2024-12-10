<?php

namespace LiveCMS;

# Імпортуємо клас Sanitizer для використання його методів фільтрації
use LiveCMS\Helpers\Sanitizer;

/**
 * Клас Constants забезпечує доступ до глобальних констант та змінних.
 */

class Constants
{
    /**
     * Масив для збереження значень глобальних змінних та констант.
     *
     * @var array<string, mixed>
     */

    private static array $data;

    /**
     * Ініціалізує значення глобальних змінних та констант.
     */

    private static function initializeData(): void
    {
        self::$data = [
            'TM' => null,                                                                                       // Поточний час у вигляді timestamp
            'PHP_SELF' => Sanitizer::filter($_SERVER['PHP_SELF'] ?? ''),                                        // Шлях до скрипта
            'HTTP_HOST' => Sanitizer::filter($_SERVER['HTTP_HOST'] ?? ''),                                      // Доменне ім'я хоста
            'SERVER_NAME' => Sanitizer::filter($_SERVER['SERVER_NAME'] ?? ''),                                  // Ім'я сервера
            'HTTP_REFERER' => Sanitizer::filter($_SERVER['HTTP_REFERER'] ?? 'none'),                            // URL попередньої сторінки
            'BROWSER' => Sanitizer::filter($_SERVER['HTTP_USER_AGENT'] ?? 'none'),                              // Інформація про браузер
            'IP' => Sanitizer::filter(filter_var($_SERVER['REMOTE_ADDR'] ?? '', FILTER_VALIDATE_IP) ?? ''),     // IP-адреса клієнта
            'SCHEME' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://',    // Протокол (HTTP/HTTPS)
            'REQUEST_URI' => Sanitizer::filter($_SERVER['REQUEST_URI'] ?? ''),                                  // URI запиту
        ];
    }

    /**
     * Отримує значення константи або властивості за її ім'ям.
     *
     * @param string $name Назва константи або властивості.
     * @return mixed Значення константи або властивості.
     * @throws \InvalidArgumentException Якщо константа або властивість не знайдена.
     */

    public static function get(string $name): mixed
    {
        # Ініціалізація значень при першому зверненні до класу
        if (!isset(self::$data)) {
            self::initializeData();
        }

        # Ініціалізація значення 'TM' (поточний час), якщо воно ще не встановлене
        if ($name === 'TM' && self::$data[$name] === null) {
            self::$data[$name] = time();
        }

        # Перевірка наявності запитуваного значення у масиві
        if (array_key_exists($name, self::$data)) {
            return self::$data[$name];
        }

        # Викидаємо виключення, якщо значення не знайдено
        throw new \InvalidArgumentException("Константа або властивість з іменем '{$name}' не знайдена.");
    }
}
