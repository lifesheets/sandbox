<?php

require_once('./system/classes/DatabaseHandler.php');
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

# Встановлює підключення до бази даних.
DatabaseHandler::connect('mariadb-11.2', 'sandbox', 'root', '');

if (DatabaseHandler::isConnected()) {
    echo 'Підключення до бази даних встановлене.';
} else {
    echo 'Підключення до бази даних не встановлене.';
}

DatabaseHandler::closeConnection();
# Закриття буферизації та виведення вмісту буфера на екран
ob_end_flush();
