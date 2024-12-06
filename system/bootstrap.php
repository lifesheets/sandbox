<?php

require_once('./system/classes/DatabaseHandler.php');
# Старт буферизації виводу
ob_start();

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
