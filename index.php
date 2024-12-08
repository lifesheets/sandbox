<?php

// Визначення кореневого шляху проєкту
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('LCMS', ROOT . '/System');

// Підключення автозавантажувача
require_once LCMS . '/Classes/Autoloader.php';

// Підключення обробника помилок
require_once LCMS . '/Classes/ErrorHandler.php';

// Реєстрація автозавантажувача та ініціалізація обробника помилок
use System\Classes\Autoloader;
use System\Classes\ErrorHandler;

Autoloader::register();
ErrorHandler::init();
