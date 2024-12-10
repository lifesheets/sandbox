<?php

namespace LiveCMS\Libraries;

use LiveCMS\Helpers\Direct;
use LiveCMS\Libraries\LoggerHandler;

/**
 * Клас для завантаження модулів.
 */

class ModuleLoader
{
    /**
     * Підключає файл, якщо він існує.
     *
     * @param string $filePath Шлях до файлу.
     * @return bool Повертає true, якщо файл був успішно підключений, інакше false.
     */

    private static function includeFile(string $filePath): bool
    {
        try {
            // Перевіряємо, чи існує файл.
            if (Direct::existsPath($filePath)) {
                // Підключаємо файл.
                require_once ROOT . $filePath;
                // Логування успішного підключення.
                LoggerHandler::log("Файл успішно підключено: $filePath", 'INFO');
                return true;
            }
            // Логування у разі відсутності файлу.
            LoggerHandler::log("Файл не знайдено: $filePath", 'WARNING');
            return false;
        } catch (\Exception $e) {
            // Логування критичної помилки при підключенні файлу.
            LoggerHandler::log("Помилка підключення файлу $filePath: " . $e->getMessage(), 'CRITICAL');
            return false;
        }
    }

    /**
     * Обробляє запит та завантажує модуль, якщо він існує.
     *
     * @param string|null $base Основна директорія модуля.
     * @param string|null $path Шлях до папки модуля.
     * @param string|null $subpath Підкаталог модуля.
     * @param string|null $section Назва модуля.
     */

    public static function processRequest(?string $base, ?string $path, ?string $subpath, ?string $section): void
    {
        try {
            # Шлях за замовчуванням, якщо модуль не знайдено.
            $defaultModule = '/plugins/main/index.php';

            # Перевіряємо наявність базової директорії.
            if (Direct::existsPath("/$base/", 'dir')) {
                # Перевіряємо підкаталоги і підключаємо відповідні файли.
                if (Direct::existsPath("/$base/$path/$subpath/", 'dir')) {
                    if (!self::includeFile("/$base/$path/$subpath/$section.php")) {
                        if (!self::includeFile("/$base/$path/$subpath/index.php")) {
                            self::includeFile($defaultModule);
                        }
                    }
                } elseif (Direct::existsPath("/$base/$path/", 'dir')) {
                    if (!self::includeFile("/$base/$path/$section.php")) {
                        if (!self::includeFile("/$base/$path/index.php")) {
                            self::includeFile($defaultModule);
                        }
                    }
                } else {
                    if (!self::includeFile("/$base/$section.php")) {
                        if (!self::includeFile("/$base/index.php")) {
                            self::includeFile($defaultModule);
                        }
                    }
                }
            } else {
                # Якщо базова директорія відсутня, підключаємо модуль за замовчуванням.
                self::includeFile($defaultModule);
            }
        } catch (\Exception $e) {
            # Логування критичної помилки при обробці запиту.
            LoggerHandler::log("Помилка обробки запиту: " . $e->getMessage(), 'CRITICAL');
            # Відправка клієнту коду помилки 500.
            http_response_code(500);
            echo "Сталася помилка під час обробки запиту. Спробуйте пізніше.";
        }
    }

    /**
     * Перевіряє, чи містить URI недопустимі параметри.
     *
     * @param array $params Масив параметрів для перевірки.
     * @return bool Повертає true, якщо URI містить недопустимі параметри.
     */

    public static function hasInvalidParams(array $params): bool
    {
        try {
            # Перевірка URI на наявність недопустимих параметрів.
            $hasInvalid = array_reduce($params, fn($carry, $param) => $carry || str_contains($_SERVER['REQUEST_URI'], "$param="), false);
            # Логування результатів перевірки.
            LoggerHandler::log("Перевірено URI на недопустимі параметри: " . json_encode($params) . " - Результат: " . ($hasInvalid ? 'true' : 'false'), 'DEBUG');
            return $hasInvalid;
        } catch (\Exception $e) {
            # Логування критичної помилки при перевірці параметрів.
            LoggerHandler::log("Помилка перевірки параметрів URI: " . $e->getMessage(), 'CRITICAL');
            return false;
        }
    }
}
