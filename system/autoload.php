<?php

# Визначення кореневого шляху програми
define('ROOT', $_SERVER['DOCUMENT_ROOT']);                                              // Поточна директорія як корінь програми
define('LCMS', ROOT . '/system');                                                       // Базова директорія системи LiveCMS

# Перевірка існування системних файлів
if (!file_exists(LCMS . '/bootstrap.php')) {
    error_log('Відсутні необхідні системні файли', 0);
    exit('Системні файли не знайдено.');
}

# Функція для рекурсивного пошуку PHP-файлів
require_once LCMS . '/functions/requires/RecursiveFileFinder.php';                      

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

