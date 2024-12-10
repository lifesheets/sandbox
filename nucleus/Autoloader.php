<?php

/**
 * @package LiveCMS
 * @version 1.0.0
 * @author  ua.livefox
 * @license MIT
 * @link    www.livecms.online
 * 
 * Клас для автозавантаження файлів за допомогою namespace.
 *
 * Цей скрипт реалізує автозавантаження за стандартом PSR-4, 
 * дозволяючи завантажувати всі частини LiveCMS 1.0.0 Alpha без використання Composer.
 * 
 * Хоча цей файл не є повноцінним класом, він зберігає назву для 
 * забезпечення зворотної сумісності з попередніми версіями LiveCMS.
 * 
 * Приклад використання:
 * require_once(ROOT . '/nucleus/Autoloader.php');
 * $view = new LiveCMS\View;  
 * $view->testInstall();
 * 
 */

# Визначення шляху до директорії з класами
define('LCMS_CLASSES_DIR', __DIR__ . '/../nucleus/classes/');

# Оголошення глобальних функцій
require_once(LCMS_CLASSES_DIR . "/functions.php");

spl_autoload_register(function ($class) {

    # Префікс класу
    $prefix = 'LiveCMS\\';

    # Чи використовує клас простір імен з префіксом?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        # Якщо ні, перейти до наступного зареєстрованого автозавантажувача
        return;
    }

    # Відрізати частину префікса
    $relative_class = substr($class, $len);

    # Побудувати шлях до файлу для включення
    $file = LCMS_CLASSES_DIR . str_replace('\\', '/', $relative_class) . '.php';

    # Якщо файл існує, підключити його
    if (file_exists($file)) {
        require_once($file);
    }
});