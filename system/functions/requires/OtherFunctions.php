<?php

/**
 * Видаляє потенційно небезпечні скрипти та символи зі строки.
 *
 * @param string|null $string Вхідний рядок для обробки.
 * @return string Очищений рядок.
 */

function remove_script(?string $string = null): string
{
    if ($string === null) {
        return '';
    }

    // Видалення неприпустимих символів (NULL, керуючі символи та DEL).
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/u', '', $string);

    // Чорний список небезпечних тегів та атрибутів
    $blacklist = [
        // Небезпечні теги.
        'vbscript', 'expression', 'applet', 'xml', 'blink', 'embed', 'object', 
        'frameset', 'ilayer', 'layer', 'bgsound',
        // Обробники подій.
        'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 
        'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 
        'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 
        'onblur', 'onbounce', 'oncellchange', 'onchange', 'oncontextmenu', 
        'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 
        'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 
        'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 
        'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 
        'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 
        'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 
        'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 
        'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 
        'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 
        'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 
        'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 
        'onstart', 'onstop', 'onsubmit', 'onunload'
    ];

    foreach ($blacklist as $keyword) {
        $string = preg_replace('/' . preg_quote($keyword, '/') . '/iu', '', $string);
    }

    return $string;
}

/**
 * Повне видалення символів з тексту без заміни.
 *
 * Ця функція видаляє всі спеціальні символи з переданого тексту, які визначені у масиві `$special_chars`,
 * а також символи '%20' та '+' для видалення пробілів і пробілів в URL.
 * Функція також обрізає текст від крапок, дефісів і підкреслень на початку та в кінці рядка.
 * Повертає текст, оброблений функцією `htmlspecialchars()` для запобігання XSS-атакам.
 *
 * @param string $text Текст, з якого потрібно видалити спеціальні символи.
 * @return string Повертає текст без спеціальних символів.
 */

function clearspecialchars(?string $text): string
{
    // Возвращаем пустую строку, если входные данные не являются строкой
    if (is_null($text) || !is_string($text)) {
        return '';
    }
    // Массив специальных символов для удаления
    $specialChars = ['?', '[', ']', '/', '\\', '=', '<', '>', ':', ';', ',', "'", '"', '&', '$', '#', '*', '(', ')', '|', '~', '`', '!', '{', '}', '%', '+', chr(0)];
    // Замена неразрывного пробела на обычный пробел
    $text = preg_replace('/\x{00a0}/u', ' ', $text);
    // Удаление специальных символов
    $text = str_replace($specialChars, '', $text);
    // Удаление '%20' и '+' (пробелы в URL)
    $text = str_replace(['%20', '+'], '', $text);
    // Обрезание точек, дефисов и подчеркиваний в начале и конце строки
    $text = trim($text, '.-_');
    // Экранирование HTML-символов для защиты от XSS
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

