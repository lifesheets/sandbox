<?php

# Додавання заголовка політики безпеки контенту (CSP) для запобігання атакам XSS (корисно у HTML-виводі)
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self';");

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

# Перевірка прямого доступу, щоб уникнути проблем з обходом шляху чи доступу до заборонених зон
if (php_sapi_name() !== 'cli' && !empty($_SERVER['SCRIPT_FILENAME']) && strpos(realpath($_SERVER['SCRIPT_FILENAME']), ROOT) !== 0) {
    # Заборонити прямий доступ, якщо скрипт знаходиться поза кореневою директорією
    http_response_code(403);
    die('Заборонено');
}

# Підключення автозавантажувача класів
require_once(ROOT . '/nucleus/Autoloader.php');

# Підключення необхідних бібліотек.
use LiveCMS\Libraries\ModuleLoader;
use LiveCMS\Libraries\LoggerHandler;
use LiveCMS\Libraries\ErrorHandler;
use LiveCMS\Helpers\Sanitizer;
use LiveCMS\Helpers\Direct;

# Ініціалізація логування.
LoggerHandler::init();
ErrorHandler::init();

# Обробка запиту із логуванням помилок.
try {
    # Основні параметри запиту.
    $base = Sanitizer::filter(Direct::get('base'));
    $path = Sanitizer::filter(Direct::get('path'));
    $subpath = Sanitizer::filter(Direct::get('subpath'));
    $section = Sanitizer::filter(Direct::get('section'));

    # Перевірте наявність параметрів в URI.
    if (ModuleLoader::hasInvalidParams(['base', 'path', 'subpath', 'section'])) {
        LoggerHandler::log('Invalid parameters detected in the request.', 'WARNING');
        LiveCMS\Helpers\Redirect::to('/');
    }
    # Логіка обробки запиту.
    ModuleLoader::processRequest($base, $path, $subpath, $section);
} catch (\Throwable $exception) {
    LoggerHandler::log('An error occurred while processing the request: ' . $exception->getMessage(), 'CRITICAL');
}

