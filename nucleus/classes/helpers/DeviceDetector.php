<?php

namespace Nucleus\Helpers;

use Nucleus\Constants;

/**
 * Класс, який визначає, чи є пристрій мобільним.
 */

class DeviceDetector
{
    /**
     * Перевіряє, чи є пристрій мобільним.
     *
     * @return bool True, якщо пристрій мобільний.
     */

    public static function isMobile(): bool
    {
        # Список ключових слів для визначення мобільних пристроїв.
        $mobileDevices = ['iphone', 'android', 'mobile', 'ipad', 'ipod', 'blackberry', 'windows phone'];
        $agent = strtolower(Constants::get('BROWSER') ?? '');

        # Перевірка, чи входить одне з ключових слів у рядок User-Agent.
        foreach ($mobileDevices as $device) {
            if (str_contains($agent, $device)) {
                return true;
            }
        }

        return false;
    }
}
