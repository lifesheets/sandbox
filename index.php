<?php

# Встановлення заголовків HTTP
header('Content-Type: text/html; charset=UTF-8');                                       // Встановлення кодування UTF-8
header('Powered: LiveCMS');                                                             // Ідентифікатор CMS у заголовку
header('Cache-control: public');                                                        // Публічне кешування
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 60 * 60 * 24) . ' GMT');         // Термін дії кешу — 1 день

// Визначення кореневого шляху проєкту
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('LCMS', ROOT . '/system');

// Підключення автозавантажувача
require_once LCMS . '/Autoloader.php';

// Підключення обробника помилок
require_once LCMS . '/Classes/ErrorHandler.php';

// Реєстрація автозавантажувача та ініціалізація обробника помилок
use System\Autoloader;
use System\Classes\ErrorHandler;

Autoloader::register();
ErrorHandler::init();
