<?php

# Старт буферизації виводу
ob_start();

# Встановлюємо унікальне ім'я сесії
session_name('SID');

# Запуск сесій
if (!session_start()) {
    // Якщо не вдалося запустити сесію, записуємо повідомлення в лог і завершуємо виконання
    error_log('Не вдалося запустити сесію', 0);
    exit('Помилка запуску сесії.');
}

# Перевірка ID сесії
$sessID = session_id();

# Генеруємо новий ID сесії, якщо поточний некоректний
if (!preg_match('#[A-Za-z0-9]{32}#i', $sessID)) {
    $sessID = md5((string) random_int(100000, 999999));
}

# Встановлення заголовків HTTP
header('Content-Type: text/html; charset=UTF-8');                                       // Встановлення кодування UTF-8
header('Powered: LiveCMS');                                                             // Ідентифікатор CMS у заголовку
header('Cache-control: public');                                                        // Публічне кешування
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 60 * 60 * 24) . ' GMT');         // Термін дії кешу — 1 день

# Встановлює підключення до бази даних.
DatabaseHandler::connect('mariadb-11.2', 'sandbox', 'root', '');

# Перевірка на підключення до бази даних.
if (!DatabaseHandler::isConnected()) {
    exit('Підключення до бази даних не встановлене.');
}

# Основні параметри запиту.
$base = sanitize(Direct::get('base'));
$path = sanitize(Direct::get('path'));
$subpath = sanitize(Direct::get('subpath'));
$section = sanitize(Direct::get('section'));

# Перевірка наявності параметрів у URI.
if (hasInvalidParams(['base', 'path', 'subpath', 'section'])) {
    redirect('/');
}

# Логіка обробки запиту.
processRequest($base, $path, $subpath, $section);

# Закриття підключення до бази даних
DatabaseHandler::closeConnection();

# Закриття буферизації та виведення вмісту буфера на екран
ob_end_flush();
