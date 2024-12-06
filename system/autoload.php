<?php

# Визначення кореневого шляху програми
define('ROOT', $_SERVER['DOCUMENT_ROOT']);                                              // Поточна директорія як корінь програми
define('LCMS', ROOT . '/system');                                                       // Базова директорія системи LiveCMS

# Перевірка існування системних файлів
if (!file_exists(LCMS . '/bootstrap.php')) {
    error_log('Відсутні необхідні системні файли', 0);
    exit('Системні файли не знайдено.');
}

# Підключення файлу для рекурсивного пошуку PHP-файлів
require_once LCMS . '/functions/requires/RecursiveFileFinder.php';

# Підключення файлу з іншими функціями
require_once LCMS . '/functions/requires/OtherFunctions.php';

# Підключення файлу з константами програми
require_once LCMS . '/constants.php';

# Підключення файлу для ініціалізації модулів
require_once LCMS . '/functions/requires/initModules.php';

# Отримання списку всіх PHP-файлів у папці functions, виключаючи 'requires'
$function_files = array_filter(
    // Рекурсивний пошук файлів
    getPhpFilesFromDirectory(LCMS . '/functions'),
    function ($path) {
        // Фільтрація шляхів
        return strpos($path, 'system/functions/requires') === false;
    }
);

# Завантаження всіх функцій
foreach ($function_files as $function_file) {
    require_once($function_file);
}

/**
 * Реєстрація автозавантажувача класів.
 * @param string $class_name Ім'я класу, який потрібно завантажити.
 */

spl_autoload_register(function ($class_name) {
    # Статична змінна для збереження карти класів
    static $classMap = null;
    # Ліниве завантаження карти класів (завантажується тільки один раз)
    if ($classMap === null) {
        # Отримання карти всіх PHP-файлів у вказаній директорії.
        $classMap = getPhpFilesFromDirectory(LCMS . '/classes');
    }
    # Перевірка існування класу у карті і підключення відповідного файлу
    $class_name_lower = strtolower($class_name); # Ім'я класу в нижньому регістрі
    # Проходження по всіх класах у мапі класів для пошуку відповідного файлу
    foreach ($classMap as $class => $path) {
        # Перевірка, чи співпадає ім'я класу (ігноруючи регістр) з іменем, яке шукаємо
        if (strtolower($class) === $class_name_lower) {
            # Підключення файлу, якщо клас знайдено
            require_once $path;
            # Завершення виконання функції після підключення файлу
            return;
        }
    }
    # Вивід повідомлення, якщо клас не знайдено (для налагодження)
    echo "Клас $class_name не знайдено.<br>";
});
