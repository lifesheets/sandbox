<?php

require_once ('./system/classes/DatabaseHandler.php');

# Встановлює підключення до бази даних.
DatabaseHandler::connect();

if (DatabaseHandler::isConnected()) {
    echo 'Підключення до бази даних встановлене.';
} else {
    echo 'Підключення до бази даних не встановлене.';
}

DatabaseHandler::closeConnection();