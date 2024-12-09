<?php

namespace Nucleus\Helpers;

class Sanitizer
{

    /**
     * Видаляє потенційно небезпечні скрипти та символи зі строки.
     *
     * @param string|null $string Вхідний рядок для обробки.
     * @return string Очищений рядок.
     */

    public static function removeScript(?string $string = null): string
    {
        if ($string === null) {
            return '';
        }

        $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/u', '', $string);

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
     * @param string $text Текст, з якого потрібно видалити спеціальні символи.
     * @return string Повертає текст без спеціальних символів.
     */

    public static function clearSpecialChars(?string $text): string
    {
        if (is_null($text) || !is_string($text)) {
            return '';
        }
        $specialChars = ['?', '[', ']', '/', '\\', '=', '<', '>', ':', ';', ',', "'", '"', '&', '$', '#', '*', '(', ')', '|', '~', '`', '!', '{', '}', '%', '+', chr(0)];
        $text = preg_replace('/\x{00a0}/u', ' ', $text);
        $text = str_replace($specialChars, '', $text);
        $text = str_replace(['%20', '+'], '', $text);
        $text = trim($text, '.-_');
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Фільтрує рядок для безпечного виведення.
     *
     * @param string $data Вхідний рядок.
     * @return string Очищений рядок.
     */

    public static function filter(string $data): string
    {
        return htmlspecialchars(self::removeScript($data), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
