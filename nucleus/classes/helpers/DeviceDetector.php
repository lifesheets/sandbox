<?php

namespace LiveCMS\Helpers;

use LiveCMS\Constants;
use LiveCMS\Libraries\LoggerHandler;

/**
 * Клас, що визначає, чи є пристрій мобільним.
 */

class DeviceDetector
{
    /**
     * Перевіряє, чи є пристрій мобільним.
     *
     * Цей метод аналізує User-Agent браузера та визначає, 
     * чи належить пристрій до мобільних.
     *
     * @return bool True, якщо пристрій мобільний; False, якщо ні.
     */

    public static function isMobile(): bool
    {
        # Список ключових слів для визначення мобільних пристроїв.
        $mobileDevices = ['iphone', 'android', 'mobile', 'ipad', 'ipod', 'blackberry', 'windows phone'];

        try {
            # Отримуємо User-Agent браузера.
            $agent = strtolower(Constants::get('BROWSER') ?? '');
            # Логуємо отриманий User-Agent.
            LoggerHandler::log("User-Agent для визначення пристрою: {$agent}", 'INFO');

            # Перевіряємо наявність ключових слів у User-Agent.
            foreach ($mobileDevices as $device) {
                if (str_contains($agent, $device)) {
                    # Логуємо, якщо пристрій визначено як мобільний.
                    LoggerHandler::log("Мобільний пристрій визначено: {$device}", 'INFO');
                    return true;
                }
            }

            # Логуємо, якщо пристрій не визначено як мобільний.
            LoggerHandler::log("Пристрій не є мобільним.", 'INFO');
            return false;
        } catch (\Exception $e) {
            # Логуємо помилку, якщо щось пішло не так.
            LoggerHandler::log("Помилка у DeviceDetector::isMobile: {$e->getMessage()}", 'ERROR');
            return false;
        }
    }
}
