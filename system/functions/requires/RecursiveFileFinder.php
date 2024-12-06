<?php

/**
 * Рекурсивна функція для отримання списку всіх PHP-файлів у директорії.
 * @param string $directory Шлях до директорії, яку потрібно перевірити.
 * @return array Масив з іменами класів та шляхами до файлів.
 */

function getPhpFilesFromDirectory($directory) {
    # Масив для збереження класів та їхніх шляхів
    $files = [];
    # Створення ітератора для рекурсивного обходу директорії та всіх її підкаталогів
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    # Проходимо по всім файлам у директорії
    foreach ($iterator as $file) {
        # Перевірка, чи є поточний елемент файлом та чи має він розширення .php
        if ($file->isFile() && $file->getExtension() === 'php') {
            # Витягуємо ім'я класу (без розширення .php)
            $className = basename($file->getBasename('.php'));
            # Отримуємо повний шлях до файлу
            $classPath = $file->getPathname();
            # Додаємо клас і його шлях до масиву
            $files[$className] = $classPath;
        }
    }
    # Повертаємо масив з класами та шляхами
    return $files;
}
