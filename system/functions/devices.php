<?php

/**
 * Визначає версію пристрою (мобільна чи десктоп).
 *
 * @return bool True для мобільних пристроїв.
 */

function is_mobile(): bool
{
    $mobileDevices = ['iphone', 'android', 'mobile', 'ipad', 'ipod', 'blackberry', 'windows phone'];
    $agent = strtolower(BROWSER);

    foreach ($mobileDevices as $device) {
        if (str_contains($agent, $device)) {
            return true;
        }
    }

    return false;
}
