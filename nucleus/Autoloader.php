<?php

/**
 * Клас для автозавантаження файлів за допомогою namespace.
 */

class Autoloader
{
    /**
     * Відповідність namespace та директорій.
     * Ключі — це префікси namespace, значення — відповідні директорії.
     */

    private static array $namespaceMap = [
        'Nucleus'            => 'nucleus/classes',              // Простір імен для Nucleus
        'Nucleus\\Helpers'   => 'nucleus/classes/helpers',      // Простір імен для Helpers
        'Nucleus\\Libraries' => 'nucleus/classes/libraries',    // Простір імен для Libraries
        'Nucleus\\Services'   => 'nucleus/classes/services',    // Простір імен для Services
    ];

    /**
     * Реєструє автозавантажувач.
     */

    public static function register()
    {
        // Встановлюємо глобальний обробник винятків
        set_exception_handler(function ($exception) {
            // Виводимо повідомлення про помилку, якщо стався виняток
            echo "Помилка: " . $exception->getMessage();
        });

        // Реєструємо метод автозавантаження класів
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /**
     * Метод для автозавантаження класу.
     * @param string $className Назва класу з урахуванням namespace.
     * @throws \Exception Якщо файл класу не знайдено.
     */

    private static function autoload($className)
    {
        # Проходимо по мапі namespace, щоб знайти відповідний шлях
        foreach (self::$namespaceMap as $namespacePrefix => $directory) {
            # Перевіряємо, чи починається ім'я класу з префікса namespace
            if (str_starts_with($className, $namespacePrefix)) {
                # Видаляємо префікс namespace та створюємо відносний шлях до файлу
                $relativeClass = substr($className, strlen($namespacePrefix));
                # Видаляємо зайві слеші
                $relativeClass = ltrim($relativeClass, '\\'); 
                $filePath = ROOT . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

                # Перевіряємо, чи існує файл за сформованим шляхом
                if (file_exists($filePath)) {
                    # Підключаємо файл, якщо він існує
                    require_once($filePath);
                    return true;
                }
            }
        }

        # Логування та виняток, якщо файл класу не знайдено
        if (class_exists('Nucleus\Libraries\LoggerHandler')) {
            # Якщо існує LoggerHandler, використовуємо його для логування
            \Nucleus\Libraries\LoggerHandler::log('
                <h2>LiveCMS - Critical Error</h2>
                <p>
                    <strong>Class:</strong>
                    <code>' . $className . '</code> not found.
                </p>
                <p>
                    <strong>Expected path:</strong>
                    <code> ' . $filePath . '</code>
                </p>
            ', 'CRITICAL');
        } else {
            # Якщо LoggerHandler недоступний, використовуємо стандартний log
            error_log("Клас $className не знайдено.");
        }

        # Кидаємо виняток, якщо клас не знайдено
        throw new \Exception("Клас $className не знайдено.");
    }
}
