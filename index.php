<?php

# Додавання заголовка політики безпеки контенту (CSP) для запобігання атакам XSS (корисно у HTML-виводі)
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self';");

# Встановлення кодування UTF-8
header('Content-Type: text/html; charset=UTF-8');

# Ідентифікатор CMS у заголовку
header('Powered: LiveCMS');

# Публічне кешування
header('Cache-control: public');

# Термін дії кешу — 1 день
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 60 * 60 * 24) . ' GMT');

# Запобігання зміни типу контенту
header('X-Content-Type-Options: nosniff');

# Визначення кореневого шляху проєкту
if (!defined('ROOT')) {
    define('ROOT', realpath($_SERVER['DOCUMENT_ROOT']));
}

# Перевірка прямого доступу, щоб уникнути проблем із обходом шляху чи доступу до заборонених зон
if (php_sapi_name() !== 'cli' && !empty($_SERVER['SCRIPT_FILENAME']) && strpos(realpath($_SERVER['SCRIPT_FILENAME']), ROOT) !== 0) {
    # Заборонити прямий доступ, якщо скрипт знаходиться поза кореневою директорією
    http_response_code(403);
    die('Заборонено');
}

# Підключення автозавантажувача класів
require_once(ROOT . '/nucleus/Autoloader.php');

# Виклик функції автозавантаження (якщо потрібно)
Autoloader::register();

# Підключення простору імен для логування
use \Nucleus\Libraries\LoggerHandler;

# Ініціалізація логгера
LoggerHandler::init();
