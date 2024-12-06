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
